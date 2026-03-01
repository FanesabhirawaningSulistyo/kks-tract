{{--
|--------------------------------------------------------------------------
| partials/task-export-btn.blade.php
|--------------------------------------------------------------------------
| Partial kecil berisi tombol Export + dropdown PDF/Excel.
| Di-include di dalam .header-actions pada kelola-task.blade.php:
|   @include('partials.task-export-btn')
|
| Fungsi toggleExportMenu(), exportPDF(), exportExcel() didefinisikan
| di partials/task-export.blade.php yang di-include di bagian bawah halaman.
--}}

<div style="position:relative;display:inline-block;">
    <button class="btn-action btn-outline-primary" onclick="toggleExportMenu(event)">
        <i class="bx bx-export"></i> Export
        <i class="bx bx-chevron-down" style="margin-left:2px;font-size:12px;"></i>
    </button>

    <div id="exportDropdown"
         style="display:none;position:absolute;right:0;top:calc(100% + 6px);
                background:white;border:1px solid var(--gray-200);border-radius:10px;
                box-shadow:0 8px 24px rgba(0,0,0,.12);z-index:999;
                min-width:180px;overflow:hidden;">

        {{-- Export PDF --}}
        <button onclick="exportPDF()"
                style="width:100%;padding:11px 16px;border:none;background:none;
                       text-align:left;font-size:13px;font-weight:600;color:var(--gray-800);
                       cursor:pointer;display:flex;align-items:center;gap:10px;transition:background .15s;">
            <span style="width:28px;height:28px;background:#FEE2E2;border-radius:7px;
                         display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bx bxs-file-pdf" style="color:#EF4444;font-size:15px;"></i>
            </span>
            Export PDF
            <span style="margin-left:auto;font-size:10px;color:var(--gray-400);font-weight:500;">Preview</span>
        </button>

        <div style="height:1px;background:var(--gray-100);margin:0 12px;"></div>

        {{-- Export Excel --}}
        <button onclick="exportExcel()"
                style="width:100%;padding:11px 16px;border:none;background:none;
                       text-align:left;font-size:13px;font-weight:600;color:var(--gray-800);
                       cursor:pointer;display:flex;align-items:center;gap:10px;transition:background .15s;">
            <span style="width:28px;height:28px;background:#D1FAE5;border-radius:7px;
                         display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                <i class="bx bxs-spreadsheet" style="color:#059669;font-size:15px;"></i>
            </span>
            Export Excel
            <span style="margin-left:auto;font-size:10px;color:var(--gray-400);font-weight:500;">Download</span>
        </button>

    </div>
</div>