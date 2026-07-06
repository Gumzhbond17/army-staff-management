# Laravel Blade & PDF (mPDF) — Production Deploy & Update Guide
### Army Staff Management · Laravel 13 · mPDF 8.3 · Windows 10 / IIS

This guide covers how Blade views and mPDF-generated PDFs behave in production, how to
deploy them, how to update them, and how to fix the image/font/permission problems that
are specific to Windows + IIS.

---

## 1. The key concept: Blade and PDF do NOT go through `npm run build`

This is the single most important thing to understand, because it changes your update routine.

| Layer | Built by | Deploy by |
|---|---|---|
| Vue / JS / Tailwind CSS | `npm run build` (Vite) → `public/build/` | copy `public/build/` |
| **Blade views (`.blade.php`)** | **Laravel compiles them to PHP at runtime, cached in `storage/framework/views`** | copy the `.blade.php` file + `php artisan view:clear` |
| **PDF logic (controller)** | nothing — it's plain PHP | copy the controller `.php` file |
| mPDF library | Composer | already in `vendor/` |

So when you change a **Blade template or a PDF view**, you do **NOT** run `npm run build`.
You copy the changed `.blade.php` / controller files and clear the view cache. That's it.

> **Why `view:clear` matters:** Laravel caches a compiled copy of every Blade file in
> `storage/framework/views`. If you copy a new `.blade.php` but don't clear that cache,
> the **old compiled version keeps rendering** and your change appears to do nothing.

---

## 2. How mPDF renders — and why images break

mPDF runs **entirely on the server**. It is not a browser. When it sees an `<img>` tag, it
cannot make an HTTP request to your own site to fetch the picture. This is why images that
work fine on a web page show as a broken ✕ in the PDF.

**Rule: in a PDF Blade view, never reference images by URL.**

| In a PDF view, DON'T use | Use instead |
|---|---|
| `{{ asset('storage/...') }}` | absolute disk path or base64 |
| `{{ url('...') }}` | absolute disk path or base64 |
| `http://localhost/storage/...` | absolute disk path or base64 |

### 2a. The reliable fix — embed images as base64

This works on every Windows/IIS setup because it puts the image data directly into the HTML,
so mPDF never has to find or fetch a file.

**Logo:**
```php
@php
    $logoPath = public_path('images/logo.png');   // <-- set to your real logo path
@endphp
@if (file_exists($logoPath))
    <img src="data:image/png;base64,{{ base64_encode(file_get_contents($logoPath)) }}" width="80">
@endif
```

**Employee photo:**
```php
@php
    // $employee->photo may be a filename ('abc.jpg') or a relative path
    // ('employees/photos/abc.jpg'). Adjust the concatenation to match what your
    // database column actually stores.
    $photoPath = storage_path('app/public/' . $employee->photo);
    $ext = strtolower(pathinfo($photoPath, PATHINFO_EXTENSION));
    $mime = $ext === 'png' ? 'image/png' : 'image/jpeg';
@endphp
@if ($employee->photo && file_exists($photoPath))
    <img src="data:{{ $mime }};base64,{{ base64_encode(file_get_contents($photoPath)) }}" width="100">
@else
    {{-- optional placeholder when there is no photo --}}
@endif
```

> The `@if (file_exists(...))` guard is important: without it, a missing file throws a
> fatal error and the whole PDF fails instead of just skipping the image.

### 2b. Alternative — absolute file path (simpler, slightly less robust)

mPDF also accepts a local absolute path directly:
```php
<img src="{{ storage_path('app/public/' . $employee->photo) }}" width="100">
```
This usually works, but base64 avoids all Windows path-quirk and permission edge cases,
so prefer base64 for the PDF.

---

## 3. mPDF on IIS — the temp directory (common cause of PDF failures)

mPDF writes temporary files while building a PDF. On Windows/IIS, the account that runs PHP
(`IIS_IUSRS`) must be able to **write** to mPDF's temp folder, or PDF generation fails with
cryptic errors.

**Set an explicit, writable temp dir when you create the mPDF instance** (in your controller):
```php
$mpdf = new \Mpdf\Mpdf([
    'tempDir' => storage_path('app/mpdf-tmp'),
    'mode'    => 'utf-8',
    'format'  => 'A4',
    // ... your other options
]);
```

**Create the folder and grant write access** (on the client, cmd/PowerShell as Admin):
```bat
mkdir C:\apps\army-staff\storage\app\mpdf-tmp
icacls "C:\apps\army-staff\storage\app\mpdf-tmp" /grant "IIS_IUSRS:(OI)(CI)M" /T
```

---

## 4. Lao font note

Your PDF already renders Lao text correctly, so your font setup is working. Keep it that way:
- Lao Unicode needs a font that supports Lao glyphs (e.g. Phetsarath OT, Noto Sans Lao).
- If you ever see Lao text turn into boxes/`???` after a server move, it means the custom
  font isn't registered with mPDF on that machine. The fix is to register the font in mPDF's
  config (`fontDir` + `fontdata`) and ship the `.ttf` file with the app — never assume the
  font exists on the client OS.

---

## 5. Deploying a Blade/PDF change — step by step

### On the DEV machine
1. Edit the Blade view and/or controller (e.g. `resources/views/employees/pdf.blade.php`,
   `app/Http/Controllers/EmployeeController.php`).
2. Test locally (Herd) that the PDF renders with the logo and photo.
3. **No `npm run build` needed** unless you also changed Vue/JS/CSS.

### On the CLIENT machine
1. Put the app in maintenance mode:
   ```bat
   cd C:\apps\army-staff
   C:\php\php.exe artisan down
   ```
2. Copy ONLY the changed files over, preserving everything else:
   - changed Blade views → `C:\apps\army-staff\resources\views\...`
   - changed controllers → `C:\apps\army-staff\app\Http\Controllers\...`
   - **do NOT** overwrite `.env` or `storage/`
3. Clear and rebuild the view + config cache (this is what makes the change take effect):
   ```bat
   C:\php\php.exe artisan view:clear
   C:\php\php.exe artisan view:cache
   C:\php\php.exe artisan config:clear
   C:\php\php.exe artisan config:cache
   ```
4. If you added/changed the mPDF temp dir or any storage path, ensure permissions:
   ```bat
   icacls "C:\apps\army-staff\storage" /grant "IIS_IUSRS:(OI)(CI)M" /T
   ```
5. Bring it back online:
   ```bat
   C:\php\php.exe artisan up
   ```
6. Generate a PDF and confirm the logo + photo appear.

---

## 6. Quick reference — which command for which change

| What you changed | Commands to run on the client |
|---|---|
| Blade view only | `view:clear` → `view:cache` |
| Controller / PHP logic | `optimize:clear` → re-cache config/route/view |
| `config/*.php` or `.env` | `config:clear` → `config:cache` |
| Routes | `route:clear` → `route:cache` |
| Added Composer package | copy `vendor/` (built with `composer install --no-dev --optimize-autoloader` on dev) |
| Vue / JS / CSS | `npm run build` on dev → copy `public/build/` |
| Anything, unsure | `php artisan optimize:clear` then re-cache all four |

---

## 7. PDF troubleshooting (Windows / IIS)

| Symptom | Cause / Fix |
|---|---|
| Logo / photo show as broken ✕ | Image referenced by URL — switch to base64 or absolute path (Section 2) |
| PDF fails / 500 only when generating | mPDF temp dir not writable — set `tempDir` + grant `IIS_IUSRS` write (Section 3) |
| Lao text shows as boxes / `???` | Lao font not registered with mPDF on this machine (Section 4) |
| Photo missing for SOME employees | Those files don't exist on disk — the `@if(file_exists())` guard skips them safely |
| "Allowed memory exhausted" on big PDFs | Raise `memory_limit` in `C:\php\php.ini` (e.g. `512M`), then `iisreset` |
| Blade edit has no effect | Forgot `view:clear` — old compiled view still cached |
| Image works on web page but not PDF | Expected — web uses HTTP (junction), PDF is server-side (needs disk path/base64) |

---

## 8. The two rules that prevent most update problems

1. **Blade/PDF changes = copy files + `view:clear`.** Never `npm run build` for these.
2. **Never let the PDF reference an image by URL.** Always disk path or base64, because mPDF
   cannot fetch your site's own URLs.
