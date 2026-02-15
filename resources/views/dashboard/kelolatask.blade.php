@extends('layouts.master')
@section('title', 'Kelola Task - E-Commerce Redesign')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
<style>
    /* Modern Minimalist Variables */
    :root {
        --primary-blue: #4F46E5;
        --primary-light: #EEF2FF;
        --success-green: #10B981;
        --success-light: #D1FAE5;
        --warning-orange: #F59E0B;
        --warning-light: #FEF3C7;
        --danger-red: #EF4444;
        --danger-light: #FEE2E2;
        --purple: #8B5CF6;
        --purple-light: #EDE9FE;
        --gray-50: #F9FAFB;
        --gray-100: #F3F4F6;
        --gray-200: #E5E7EB;
        --gray-300: #D1D5DB;
        --gray-400: #9CA3AF;
        --gray-500: #6B7280;
        --gray-600: #4B5563;
        --gray-700: #374151;
        --gray-800: #1F2937;
        --gray-900: #111827;
    }

    /* Project Header - Integrated Style */
    .project-header-card {
        background: white;
        border-radius: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 24px;
        overflow: hidden;
        border: 1px solid var(--gray-200);
    }

    .project-header-top {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--purple) 100%);
        padding: 24px 28px;
        position: relative;
        overflow: hidden;
    }

    .project-header-top::before {
        content: '';
        position: absolute;
        top: -50px;
        right: -50px;
        width: 200px;
        height: 200px;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        border-radius: 50%;
    }

    .project-header-content {
        position: relative;
        z-index: 1;
    }

    .project-icon {
        width: 48px;
        height: 48px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.3);
        font-size: 24px;
    }

    .project-title {
        color: white;
        font-weight: 700;
        font-size: 22px;
        margin-bottom: 6px;
        line-height: 1.2;
    }

    .project-desc {
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 0;
        font-size: 14px;
        line-height: 1.5;
    }

    .project-meta {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        margin-top: 12px;
    }

    .project-meta-item {
        display: flex;
        align-items: center;
        gap: 6px;
        color: rgba(255, 255, 255, 0.95);
        font-size: 13px;
        font-weight: 500;
    }

    .project-meta-item i {
        font-size: 16px;
    }

    .btn-invite {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
        color: white;
        font-weight: 600;
        padding: 8px 16px;
        border-radius: 8px;
        transition: all 0.3s ease;
        font-size: 14px;
    }

    .btn-invite:hover {
        background: rgba(255, 255, 255, 0.3);
        border-color: rgba(255, 255, 255, 0.5);
        color: white;
        transform: translateY(-2px);
    }

    /* Statistics Bar - Integrated in Header */
    .project-stats-bar {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        border-top: 1px solid var(--gray-200);
        background: var(--gray-50);
    }

    .stat-item {
        padding: 20px 28px;
        border-right: 1px solid var(--gray-200);
        display: flex;
        align-items: center;
        gap: 16px;
        transition: background 0.2s;
    }

    .stat-item:last-child {
        border-right: none;
    }

    .stat-item:hover {
        background: white;
    }

    .stat-icon-circle {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        flex-shrink: 0;
    }

    .stat-icon-circle.total { 
        background: linear-gradient(135deg, var(--primary-blue), var(--purple)); 
        color: white;
    }

    .stat-icon-circle.done { 
        background: linear-gradient(135deg, var(--success-green), #059669); 
        color: white;
    }

    .stat-icon-circle.progress { 
        background: linear-gradient(135deg, var(--warning-orange), #D97706); 
        color: white;
    }

    .stat-icon-circle.todo { 
        background: linear-gradient(135deg, var(--gray-400), var(--gray-500)); 
        color: white;
    }

    .stat-info {
        flex: 1;
        min-width: 0;
    }

    .stat-label {
        font-size: 12px;
        color: var(--gray-600);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 4px;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--gray-900);
        line-height: 1;
        margin-bottom: 4px;
    }

    .stat-percentage {
        font-size: 12px;
        color: var(--gray-500);
        font-weight: 500;
    }

    /* Dashboard Grid */
    .dashboard-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }

    @media (max-width: 992px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Chart Cards */
    .chart-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        border: 1px solid var(--gray-200);
    }

    .chart-header {
        margin-bottom: 20px;
    }

    .chart-title {
        font-size: 18px;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 4px;
    }

    .chart-subtitle {
        font-size: 14px;
        color: var(--gray-600);
    }

    /* Performer Chart Custom */
    .performer-chart {
        position: relative;
        min-height: 350px;
    }

    .chart-legend {
        display: flex;
        gap: 20px;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 12px;
        font-weight: 500;
    }

    .legend-color {
        width: 12px;
        height: 12px;
        border-radius: 2px;
    }

    .legend-color.ontime {
        background: var(--success-green);
    }

    .legend-color.early {
        background: var(--primary-blue);
    }

    .legend-color.late {
        background: var(--danger-red);
    }

    /* Progress Circle */
    .progress-circle-wrapper {
        position: relative;
        width: 200px;
        height: 200px;
        margin: 0 auto;
    }

    .progress-center-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }

    .progress-percentage {
        font-size: 36px;
        font-weight: 700;
        color: var(--gray-900);
        line-height: 1;
        margin-bottom: 4px;
    }

    .progress-label {
        font-size: 13px;
        color: var(--gray-600);
        font-weight: 500;
    }

    /* Task Sheet Container */
    .task-sheet-container {
        background: white;
        border: 1px solid var(--gray-200);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .task-sheet-header {
        background: var(--gray-50);
        border-bottom: 1px solid var(--gray-200);
        padding: 20px 24px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .task-sheet-title {
        font-size: 20px;
        font-weight: 700;
        color: var(--gray-900);
        margin-bottom: 4px;
    }

    .task-sheet-subtitle {
        font-size: 13px;
        color: var(--gray-600);
        margin: 0;
    }

    /* Task Table */
    .task-table-wrapper {
        overflow-x: auto;
        max-height: 600px;
    }

    .task-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1200px;
    }

    .task-table thead {
        position: sticky;
        top: 0;
        z-index: 10;
        background: var(--gray-50);
    }

    .task-table th {
        padding: 14px 12px;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        color: var(--gray-700);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 2px solid var(--gray-200);
        white-space: nowrap;
    }

    .task-table td {
        padding: 12px;
        border-bottom: 1px solid var(--gray-100);
        vertical-align: middle;
    }

    .task-table tbody tr {
        transition: background-color 0.2s;
    }

    .task-table tbody tr:hover {
        background-color: var(--gray-50);
    }

    /* Task Number */
    .task-number {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: var(--primary-light);
        color: var(--primary-blue);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 15px;
    }

    /* Action Buttons */
    .action-cell {
        display: flex;
        flex-direction: column;
        gap: 6px;
        width: 60px;
        align-items: center;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        border: 1px solid var(--gray-300);
        background: white;
        color: var(--gray-700);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 16px;
    }

    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .action-btn.detail:hover {
        border-color: var(--primary-blue);
        color: var(--primary-blue);
        background: var(--primary-light);
    }

    .action-btn.delete:hover {
        border-color: var(--danger-red);
        color: var(--danger-red);
        background: var(--danger-light);
    }

    /* Task Info */
    .task-info-cell {
        min-width: 280px;
        max-width: 400px;
    }

    .task-name-input {
        font-size: 15px;
        font-weight: 600;
        color: var(--gray-900);
        border: 1px solid transparent;
        background: transparent;
        padding: 6px 10px;
        border-radius: 6px;
        width: 100%;
        margin-bottom: 6px;
        transition: all 0.2s;
    }

    .task-name-input:hover {
        background: var(--gray-50);
    }

    .task-name-input:focus {
        border-color: var(--primary-blue);
        background: white;
        outline: none;
        box-shadow: 0 0 0 3px var(--primary-light);
    }

    .task-desc-input {
        font-size: 13px;
        color: var(--gray-600);
        border: 1px solid transparent;
        background: transparent;
        padding: 6px 10px;
        border-radius: 6px;
        width: 100%;
        resize: none;
        min-height: 50px;
        transition: all 0.2s;
    }

    .task-desc-input:hover {
        background: var(--gray-50);
    }

    .task-desc-input:focus {
        border-color: var(--gray-300);
        background: white;
        outline: none;
    }

    /* Select Styles */
    .compact-select {
        border: 1px solid var(--gray-300);
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 13px;
        background: white;
        color: var(--gray-800);
        cursor: pointer;
        width: 100%;
        transition: all 0.2s;
        font-weight: 500;
    }

    .compact-select:hover {
        border-color: var(--gray-400);
    }

    .compact-select:focus {
        border-color: var(--primary-blue);
        outline: none;
        box-shadow: 0 0 0 3px var(--primary-light);
    }

    .assignee-select {
        min-width: 160px;
    }

    /* PERBAIKAN: Status & Level Column - Berdampingan */
    .status-level-cell {
        min-width: 200px;
        max-width: 220px;
    }

    .status-level-container {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    /* Row 1: Select Status */
    .status-select-row {
        width: 100%;
    }

    /* Row 2: Status Badge dan Level Button berdampingan */
    .status-level-row {
        display: flex;
        align-items: center;
        gap: 8px;
        justify-content: space-between;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        text-transform: capitalize;
        flex: 1;
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }

    .status-badge:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    .status-todo { 
        background: var(--gray-100); 
        color: var(--gray-700);
        border-color: var(--gray-200);
    }

    .status-progress { 
        background: var(--warning-light); 
        color: #D97706;
        border-color: #FDE68A;
    }

    .status-review { 
        background: var(--purple-light); 
        color: var(--purple);
        border-color: #DDD6FE;
    }

    .status-done { 
        background: var(--success-light); 
        color: #059669;
        border-color: #A7F3D0;
    }

    .status-badge::before {
        content: '';
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: currentColor;
        flex-shrink: 0;
    }

    /* Level Button di samping Status Badge */
    .level-select-container {
        position: relative;
        flex-shrink: 0;
    }

    .level-display {
        display: flex;
        align-items: center;
        gap: 4px;
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        cursor: pointer;
        transition: all 0.2s;
        white-space: nowrap;
    }

    .level-display:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    }

    .level-display::after {
        content: '▼';
        font-size: 9px;
        margin-left: 2px;
        opacity: 0.7;
    }

    .level-1 { 
        background: linear-gradient(135deg, #10B981, #059669); 
        color: white;
    }

    .level-2 { 
        background: linear-gradient(135deg, var(--warning-orange), #D97706); 
        color: white;
    }

    .level-3 { 
        background: linear-gradient(135deg, var(--danger-red), #DC2626); 
        color: white;
    }

    .level-4 {
        background: linear-gradient(135deg, #8B5CF6, #7C3AED);
        color: white;
    }

    .level-5 {
        background: linear-gradient(135deg, #6366F1, #4F46E5);
        color: white;
    }

    /* Level Dropdown */
    .level-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        margin-top: 4px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
        border: 1px solid var(--gray-200);
        z-index: 100;
        min-width: 120px;
        overflow: hidden;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.2s ease;
    }

    .level-dropdown.show {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .level-option {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 14px;
        font-size: 12px;
        font-weight: 600;
        color: var(--gray-700);
        cursor: pointer;
        transition: all 0.2s;
        border-bottom: 1px solid var(--gray-100);
    }

    .level-option:last-child {
        border-bottom: none;
    }

    .level-option:hover {
        background: var(--gray-50);
        color: var(--gray-900);
    }

    .level-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .level-option[data-level="1"] .level-dot { background: #10B981; }
    .level-option[data-level="2"] .level-dot { background: var(--warning-orange); }
    .level-option[data-level="3"] .level-dot { background: var(--danger-red); }
    .level-option[data-level="4"] .level-dot { background: #8B5CF6; }
    .level-option[data-level="5"] .level-dot { background: #6366F1; }

    /* Date Display */
    .date-cell {
        min-width: 150px;
    }

    .date-info {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .date-row {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .date-label {
        font-size: 11px;
        color: var(--gray-500);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .date-value {
        font-size: 13px;
        color: var(--gray-900);
        font-weight: 600;
    }

    .timeline-status {
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 4px;
        display: inline-block;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .timeline-ontime { 
        background: var(--success-light); 
        color: var(--success-green); 
    }

    .timeline-late { 
        background: var(--danger-light); 
        color: var(--danger-red); 
    }

    /* Gantt Chart */
    .gantt-container {
        position: relative;
        height: 56px;
        background: var(--gray-50);
        border-radius: 8px;
        border: 1px solid var(--gray-200);
        overflow: visible;
        min-width: 600px;
    }

    .gantt-timeline {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
    }

    .gantt-month {
        flex: 1;
        border-right: 1px solid var(--gray-200);
        position: relative;
        min-width: 180px;
    }

    .gantt-month:last-child {
        border-right: none;
    }

    .gantt-month-label {
        position: absolute;
        top: 6px;
        left: 8px;
        font-size: 11px;
        color: var(--gray-600);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .gantt-sprint-grid {
        position: absolute;
        top: 28px;
        left: 0;
        right: 0;
        bottom: 0;
        display: flex;
        border-top: 1px solid var(--gray-200);
    }

    .gantt-sprint {
        flex: 1;
        border-right: 1px dashed var(--gray-300);
        position: relative;
    }

    .gantt-sprint:last-child {
        border-right: none;
    }

    .gantt-sprint-label {
        position: absolute;
        bottom: 4px;
        right: 4px;
        font-size: 9px;
        color: var(--gray-400);
        font-weight: 600;
    }

    /* Gantt Bar */
    .gantt-bar-container {
        position: absolute;
        top: 12px;
        height: 32px;
        cursor: move;
        user-select: none;
        z-index: 5;
    }

    .gantt-bar {
        height: 100%;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 12px;
        position: relative;
        font-size: 11px;
        font-weight: 700;
        color: white;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        transition: all 0.2s;
    }

    .gantt-bar:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .gantt-bar.todo { 
        background: linear-gradient(135deg, var(--gray-400) 0%, var(--gray-500) 100%); 
    }

    .gantt-bar.progress { 
        background: linear-gradient(135deg, var(--warning-orange) 0%, #D97706 100%); 
    }

    .gantt-bar.review { 
        background: linear-gradient(135deg, var(--purple) 0%, #7C3AED 100%); 
    }

    .gantt-bar.done { 
        background: linear-gradient(135deg, var(--success-green) 0%, #059669 100%); 
    }

    /* Resize Handles */
    .resize-handle {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 12px;
        cursor: ew-resize;
        z-index: 10;
    }

    .resize-handle.left { left: -6px; }
    .resize-handle.right { right: -6px; }

    .resize-handle::before {
        content: '';
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 4px;
        height: 20px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 2px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
    }

    .resize-handle.left::before { left: 4px; }
    .resize-handle.right::before { right: 4px; }

    /* Add Task Button */
    .add-row-btn {
        width: 100%;
        padding: 18px;
        background: var(--gray-50);
        border: none;
        border-top: 1px solid var(--gray-200);
        color: var(--primary-blue);
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .add-row-btn:hover {
        background: var(--primary-light);
        color: var(--primary-blue);
    }

    .add-row-btn i {
        font-size: 18px;
    }

    /* Action Buttons in Header */
    .header-actions {
        display: flex;
        gap: 10px;
    }

    .btn-action {
        padding: 10px 18px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-outline-primary {
        background: white;
        color: var(--primary-blue);
        border: 2px solid var(--primary-blue);
    }

    .btn-outline-primary:hover {
        background: var(--primary-blue);
        color: white;
    }

    .btn-primary {
        background: var(--primary-blue);
        color: white;
    }

    .btn-primary:hover {
        background: var(--purple);
    }

    /* Empty State */
    .empty-state {
        padding: 60px 24px;
        text-align: center;
    }

    .empty-state i {
        font-size: 64px;
        color: var(--gray-300);
        margin-bottom: 16px;
    }

    .empty-state p {
        color: var(--gray-600);
        margin-bottom: 8px;
    }

    .empty-state .text-muted {
        color: var(--gray-500);
        font-size: 14px;
    }

    /* Scrollbar */
    .task-table-wrapper::-webkit-scrollbar {
        width: 10px;
        height: 10px;
    }

    .task-table-wrapper::-webkit-scrollbar-track {
        background: var(--gray-100);
        border-radius: 5px;
    }

    .task-table-wrapper::-webkit-scrollbar-thumb {
        background: var(--gray-400);
        border-radius: 5px;
    }

    .task-table-wrapper::-webkit-scrollbar-thumb:hover {
        background: var(--gray-500);
    }

    /* Modal Styles */
    .task-detail-modal .modal-content {
        border: none;
        border-radius: 16px;
        overflow: hidden;
    }

    .task-detail-header {
        background: linear-gradient(135deg, var(--primary-blue) 0%, var(--purple) 100%);
        color: white;
        padding: 24px 28px;
    }

    .modal-title {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .task-detail-body {
        padding: 28px;
    }

    .detail-field {
        margin-bottom: 24px;
    }

    .detail-label {
        font-size: 12px;
        font-weight: 700;
        color: var(--gray-600);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 8px;
    }

    .detail-value {
        font-size: 15px;
        color: var(--gray-900);
        font-weight: 500;
    }

    /* Chart Control */
    .chart-controls {
        display: flex;
        gap: 8px;
        margin-top: 16px;
    }

    .chart-control-btn {
        padding: 6px 12px;
        background: var(--gray-100);
        border: 1px solid var(--gray-300);
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        color: var(--gray-700);
        cursor: pointer;
        transition: all 0.2s;
    }

    .chart-control-btn.active {
        background: var(--primary-blue);
        color: white;
        border-color: var(--primary-blue);
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .project-stats-bar {
            grid-template-columns: repeat(2, 1fr);
        }
        
        .stat-item:nth-child(2) {
            border-right: none;
        }
        
        .stat-item:nth-child(3) {
            border-top: 1px solid var(--gray-200);
        }
    }

    @media (max-width: 768px) {
        .project-stats-bar {
            grid-template-columns: 1fr;
        }
        
        .stat-item {
            border-right: none !important;
            border-bottom: 1px solid var(--gray-200);
        }
        
        .stat-item:last-child {
            border-bottom: none;
        }
        
        .project-header-top {
            padding: 20px;
        }
        
        .task-sheet-header {
            flex-direction: column;
            gap: 16px;
            align-items: flex-start;
        }
        
        .header-actions {
            width: 100%;
            flex-direction: column;
        }
        
        .btn-action {
            width: 100%;
            justify-content: center;
        }
        
        .dashboard-grid {
            grid-template-columns: 1fr;
        }
        
        .chart-controls {
            flex-wrap: wrap;
        }
        
        /* Responsive untuk Status & Level */
        .status-level-row {
            flex-direction: column;
            align-items: stretch;
            gap: 6px;
        }
        
        .level-select-container {
            align-self: flex-start;
        }
    }
</style>
@endpush

@section('content')
<!-- Integrated Project Header with Stats -->
<div class="project-header-card">
    <!-- Project Info Section -->
    <div class="project-header-top">
        <div class="project-header-content">
            <div class="d-flex align-items-start justify-content-between">
                <div class="d-flex align-items-start gap-3 flex-1">
                    <div class="project-icon">
                        <i class="bx bx-shopping-bag"></i>
                    </div>
                    <div>
                        <h4 class="project-title">E-Commerce Redesign Project</h4>
                        <p class="project-desc">Redesign aplikasi e-commerce dengan UI/UX modern dan peningkatan performa</p>
                        <div class="project-meta">
                            <div class="project-meta-item">
                                <i class="bx bx-calendar"></i>
                                <span>01 Jan 2026 - 31 Mar 2026</span>
                            </div>
                            <div class="project-meta-item">
                                <i class="bx bx-time"></i>
                                <span>45 hari tersisa</span>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="btn-invite" data-bs-toggle="modal" data-bs-target="#inviteTeamModal">
                    <i class="bx bx-user-plus me-1"></i>
                    Undang Tim
                </button>
            </div>
        </div>
    </div>
    <!-- Statistics Bar -->
    <div class="project-stats-bar">
        <div class="stat-item">
            <div class="stat-icon-circle total">
                <i class="bx bx-task"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Total Task</div>
                <div class="stat-value" id="totalTasks">10</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon-circle done">
                <i class="bx bx-check-circle"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Task Selesai</div>
                <div class="stat-value" id="doneTasks">5</div>
                <div class="stat-percentage" id="donePercentage">50% dari total</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon-circle progress">
                <i class="bx bx-loader-circle"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Task Progress</div>
                <div class="stat-value" id="progressTasks">3</div>
                <div class="stat-percentage" id="progressPercentage">30% dari total</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon-circle todo">
                <i class="bx bx-time-five"></i>
            </div>
            <div class="stat-info">
                <div class="stat-label">Belum Dikerjakan</div>
                <div class="stat-value" id="todoTasks">1</div>
                <div class="stat-percentage" id="todoPercentage">10% dari total</div>
            </div>
        </div>
    </div>
</div>

<!-- Dashboard Grid: Charts -->
<div class="dashboard-grid">
    <!-- Performance Chart -->
    <div class="chart-card">
        <div class="chart-header">
            <h5 class="chart-title">Performance Anggota Tim</h5>
            <p class="chart-subtitle">Distribusi penyelesaian task berdasarkan penanggung jawab</p>
        </div>
        <div class="performer-chart">
            <div id="employeePerformanceChart"></div>
            <div class="chart-legend">
                <div class="legend-item">
                    <div class="legend-color ontime"></div>
                    <span>Tepat Waktu</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color early"></div>
                    <span>Sebelum Deadline</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color late"></div>
                    <span>Terlambat</span>
                </div>
            </div>
            <div class="chart-controls">
                <button class="chart-control-btn active" onclick="changeChartView('bar')">Bar Chart</button>
                <button class="chart-control-btn" onclick="changeChartView('stacked')">Stacked Bar</button>
                <button class="chart-control-btn" onclick="changeChartView('percentage')">Persentase</button>
            </div>
        </div>
    </div>
    
    <!-- Pie Chart -->
    <div class="chart-card">
        <div class="chart-header">
            <h5 class="chart-title">Status Task</h5>
            <p class="chart-subtitle">Persentase penyelesaian task</p>
        </div>
        <div class="progress-circle-wrapper">
            <div id="progressPieChart"></div>
            <div class="progress-center-text">
                <div class="progress-percentage" id="progressPercentageText">50%</div>
                <div class="progress-label">Selesai</div>
            </div>
        </div>
    </div>
</div>

<!-- Modern Task Sheet -->
<div class="task-sheet-container">
    <div class="task-sheet-header">
        <div>
            <h5 class="task-sheet-title">Task Timeline</h5>
            <p class="task-sheet-subtitle">Drag timeline untuk mengatur periode, geser sisi kiri/kanan untuk adjust tanggal</p>
        </div>
        <div class="header-actions">
            <button class="btn-action btn-outline-primary" onclick="exportTasks()">
                <i class="bx bx-export"></i> Export
            </button>
            <button class="btn-action btn-primary" onclick="saveAllTasks()">
                <i class="bx bx-save"></i> Simpan Semua
            </button>
        </div>
    </div>
    
    <div class="task-table-wrapper">
        <table class="task-table">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th style="width: 80px;">Aksi</th>
                    <th style="width: 300px;">Task Info</th>
                    <th style="width: 160px;">Penanggung Jawab</th>
                    <th style="width: 200px;">Status & Level</th>
                    <th style="width: 160px;">Deadline</th>
                    <th style="min-width: 600px;">Timeline</th>
                </tr>
            </thead>
            <tbody id="taskBody">
                <!-- Tasks will be rendered here -->
            </tbody>
        </table>
        
        <div id="emptyState" class="empty-state" style="display: none;">
            <i class="bx bx-task"></i>
            <p class="fw-medium">Belum ada task</p>
            <p class="text-muted">Klik "Tambah Task Baru" untuk membuat task pertama Anda</p>
        </div>
    </div>
    
    <button class="add-row-btn" onclick="addNewTask()">
        <i class="bx bx-plus"></i>
        Tambah Task Baru
    </button>
</div>

<!-- Modal: Task Detail -->
<div class="modal fade task-detail-modal" id="taskDetailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="task-detail-header">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="modal-title" id="detailTaskName">Task Name</h5>
                        <small id="detailTaskId">ID: </small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
            </div>
            <div class="task-detail-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="detail-field">
                            <div class="detail-label">Deskripsi</div>
                            <div class="detail-value" id="detailTaskDesc"></div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-field">
                                    <div class="detail-label">Penanggung Jawab</div>
                                    <div class="detail-value" id="detailTaskAssignee"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="detail-field">
                                    <div class="detail-label">Status & Level</div>
                                    <div id="detailTaskStatusLevel"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="detail-field">
                                    <div class="detail-label">Timeline Status</div>
                                    <div id="detailTimelineStatus"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="detail-field">
                            <div class="detail-label">Tanggal Mulai</div>
                            <div class="detail-value fw-bold" id="detailStartDate"></div>
                        </div>
                        <div class="detail-field">
                            <div class="detail-label">Deadline</div>
                            <div class="detail-value fw-bold" id="detailEndDate"></div>
                        </div>
                        <div class="detail-field">
                            <div class="detail-label">Durasi</div>
                            <div class="detail-value" id="detailDuration"></div>
                        </div>
                        <div class="detail-field">
                            <div class="detail-label">Progress</div>
                            <div class="progress mt-2" style="height: 8px; border-radius: 4px;">
                                <div class="progress-bar" id="detailProgressBar" role="progressbar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script>
'use strict';

const employees = [
    { id: 1, name: 'Ahmad Rizki', avatar: 'AR' },
    { id: 2, name: 'Budi Santoso', avatar: 'BS' },
    { id: 3, name: 'Siti Nurhaliza', avatar: 'SN' },
    { id: 4, name: 'Dewi Lestari', avatar: 'DL' },
    { id: 5, name: 'Rudi Hartono', avatar: 'RH' }
];

let tasks = [
    { 
        id: 1, 
        nama: 'Database Design', 
        desc: 'Merancang struktur database baru untuk e-commerce dengan performa tinggi',
        assignee: 1, 
        status: 'done', 
        level: '3',
        startDate: '2026-01-05', 
        endDate: '2026-01-15',
        progress: 100
    },
    { 
        id: 2, 
        nama: 'API Development', 
        desc: 'Membuat RESTful API untuk integrasi dengan third-party services',
        assignee: 2, 
        status: 'progress', 
        level: '3',
        startDate: '2026-01-10', 
        endDate: '2026-02-20',
        progress: 60
    },
    { 
        id: 3, 
        nama: 'UI/UX Design', 
        desc: 'Desain antarmuka homepage dan dashboard dengan pengalaman pengguna terbaik',
        assignee: 3, 
        status: 'done', 
        level: '2',
        startDate: '2026-01-08', 
        endDate: '2026-01-20',
        progress: 100
    },
    { 
        id: 4, 
        nama: 'Payment Gateway', 
        desc: 'Integrasi multiple payment gateway (Midtrans, Xendit, dll)',
        assignee: 4, 
        status: 'review', 
        level: '3',
        startDate: '2026-02-01', 
        endDate: '2026-02-28',
        progress: 80
    },
    { 
        id: 5, 
        nama: 'Product Catalog', 
        desc: 'Modul katalog produk dengan filter dan pencarian advanced',
        assignee: 5, 
        status: 'progress', 
        level: '2',
        startDate: '2026-02-10', 
        endDate: '2026-03-15',
        progress: 40
    },
    { 
        id: 6, 
        nama: 'User Authentication', 
        desc: 'Sistem autentikasi dan otorisasi pengguna',
        assignee: 1, 
        status: 'done', 
        level: '2',
        startDate: '2026-01-15', 
        endDate: '2026-01-25',
        progress: 100
    },
    { 
        id: 7, 
        nama: 'Shopping Cart', 
        desc: 'Fitur keranjang belanja dengan real-time update',
        assignee: 2, 
        status: 'done', 
        level: '2',
        startDate: '2026-01-20', 
        endDate: '2026-02-05',
        progress: 100
    },
    { 
        id: 8, 
        nama: 'Admin Dashboard', 
        desc: 'Dashboard admin untuk monitoring dan manajemen',
        assignee: 3, 
        status: 'progress', 
        level: '3',
        startDate: '2026-02-15', 
        endDate: '2026-03-10',
        progress: 70
    },
    { 
        id: 9, 
        nama: 'Mobile Responsive', 
        desc: 'Optimasi tampilan untuk perangkat mobile',
        assignee: 4, 
        status: 'done', 
        level: '1',
        startDate: '2026-01-25', 
        endDate: '2026-02-10',
        progress: 100
    },
    { 
        id: 10, 
        nama: 'Testing & Deployment', 
        desc: 'Testing menyeluruh dan deployment ke production',
        assignee: 5, 
        status: 'todo', 
        level: '3',
        startDate: '2026-03-20', 
        endDate: '2026-03-31',
        progress: 0
    }
];

let taskIdCounter = tasks.length + 1;
let draggedElement = null;
let dragStartX = 0;
let dragStartLeft = 0;
let isResizing = false;
let resizeDirection = null;
const projectStart = new Date('2026-01-01');
const projectEnd = new Date('2026-03-31');

let performanceChart = null;
let progressChart = null;
let currentChartView = 'bar';
let activeDropdown = null;

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    if (activeDropdown && !event.target.closest('.level-select-container')) {
        activeDropdown.classList.remove('show');
        activeDropdown = null;
    }
});

function toggleLevelDropdown(container) {
    const dropdown = container.querySelector('.level-dropdown');
    
    if (activeDropdown && activeDropdown !== dropdown) {
        activeDropdown.classList.remove('show');
    }
    
    dropdown.classList.toggle('show');
    activeDropdown = dropdown.classList.contains('show') ? dropdown : null;
}

function changeLevel(taskId, newLevel) {
    const task = tasks.find(t => t.id === taskId);
    if (task) {
        task.level = newLevel;
        renderAllTasks();
        showNotification('Level task berhasil diubah', 'success');
    }
    
    // Close dropdown
    if (activeDropdown) {
        activeDropdown.classList.remove('show');
        activeDropdown = null;
    }
}

function getEmployeePerformanceData() {
    const today = new Date('2026-02-14'); // Tanggal referensi
    
    return employees.map(employee => {
        const employeeTasks = tasks.filter(task => task.assignee === employee.id);
        
        let onTime = 0;
        let early = 0;
        let late = 0;
        
        employeeTasks.forEach(task => {
            if (task.status === 'done') {
                const endDate = new Date(task.endDate);
                const daysDifference = Math.ceil((endDate - today) / (1000 * 60 * 60 * 24));
                
                if (daysDifference > 0) {
                    early++; // Selesai sebelum deadline
                } else if (daysDifference === 0) {
                    onTime++; // Selesai tepat waktu
                } else {
                    late++; // Terlambat
                }
            }
        });
        
        return {
            employeeName: employee.name,
            onTime,
            early,
            late,
            totalTasks: employeeTasks.length
        };
    });
}

function updateEmployeePerformanceChart(viewType = 'bar') {
    const data = getEmployeePerformanceData();
    
    if (performanceChart) {
        performanceChart.destroy();
    }
    
    const options = {
        chart: {
            type: viewType === 'bar' ? 'bar' : 'bar',
            height: 350,
            stacked: viewType === 'stacked' || viewType === 'percentage',
            toolbar: {
                show: false
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800
            }
        },
        series: [
            {
                name: 'Tepat Waktu',
                data: data.map(d => d.onTime),
                color: '#10B981'
            },
            {
                name: 'Sebelum Deadline',
                data: data.map(d => d.early),
                color: '#4F46E5'
            },
            {
                name: 'Terlambat',
                data: data.map(d => d.late),
                color: '#EF4444'
            }
        ],
        xaxis: {
            categories: data.map(d => d.employeeName),
            labels: {
                style: {
                    fontSize: '12px',
                    fontWeight: 600,
                    colors: '#6B7280'
                }
            },
            axisBorder: {
                show: true,
                color: '#E5E7EB'
            }
        },
        yaxis: {
            title: {
                text: 'Jumlah Task',
                style: {
                    fontSize: '12px',
                    color: '#6B7280'
                }
            },
            labels: {
                style: {
                    fontSize: '11px',
                    colors: '#6B7280'
                }
            },
            min: 0,
            tickAmount: 5
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: viewType === 'bar' ? '50%' : '60%',
                borderRadius: 4,
                borderRadiusApplication: 'end'
            }
        },
        dataLabels: {
            enabled: false
        },
        grid: {
            borderColor: '#E5E7EB',
            strokeDashArray: 4,
            padding: {
                top: 0,
                right: 20,
                bottom: 0,
                left: 20
            }
        },
        legend: {
            position: 'top',
            horizontalAlign: 'right',
            fontSize: '12px',
            fontWeight: 500,
            itemMargin: {
                horizontal: 10,
                vertical: 5
            }
        },
        tooltip: {
            shared: true,
            intersect: false,
            y: {
                formatter: function (value) {
                    return value + ' task'
                }
            }
        }
    };
    
    if (viewType === 'percentage') {
        options.plotOptions.bar = {
            horizontal: false,
            columnWidth: '60%',
            borderRadius: 4,
            borderRadiusApplication: 'end',
            dataLabels: {
                total: {
                    enabled: true,
                    style: {
                        fontSize: '11px',
                        fontWeight: 600
                    }
                }
            }
        };
        options.dataLabels = {
            enabled: true,
            formatter: function(val, opts) {
                const seriesIndex = opts.seriesIndex;
                const dataPointIndex = opts.dataPointIndex;
                const total = data[dataPointIndex].totalTasks;
                if (total > 0) {
                    const percentage = Math.round((val / total) * 100);
                    return percentage > 0 ? percentage + '%' : '';
                }
                return '';
            },
            style: {
                fontSize: '10px',
                colors: ['#fff']
            }
        };
    }
    
    performanceChart = new ApexCharts(document.querySelector("#employeePerformanceChart"), options);
    performanceChart.render();
}

function changeChartView(viewType) {
    currentChartView = viewType;
    
    // Update active button
    document.querySelectorAll('.chart-control-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');
    
    updateEmployeePerformanceChart(viewType);
}

function formatDate(dateString) {
    const d = new Date(dateString);
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];
    return `${d.getDate()} ${months[d.getMonth()]}`;
}

function formatFullDate(dateString) {
    const d = new Date(dateString);
    return d.toLocaleDateString('id-ID', { 
        day: 'numeric', 
        month: 'long',
        year: 'numeric'
    });
}

function daysBetween(d1, d2) {
    return Math.round(Math.abs((new Date(d1) - new Date(d2)) / (24*60*60*1000)));
}

function calculateTimelineStatus(task) {
    const today = new Date('2026-02-14');
    const end = new Date(task.endDate);
    
    if (task.status === 'done') {
        const daysDiff = Math.ceil((end - today) / (1000 * 60 * 60 * 24));
        if (daysDiff > 0) {
            return 'early'; // Sebelum deadline
        } else if (daysDiff === 0) {
            return 'ontime'; // Tepat waktu
        } else {
            return 'late'; // Terlambat
        }
    }
    
    if (end < today) return 'late';
    if (end > today) return 'early';
    return 'ontime';
}

function calculateGanttPosition(startDate, endDate) {
    const totalDays = daysBetween(projectStart, projectEnd);
    const taskStart = new Date(startDate);
    const taskEnd = new Date(endDate);
    
    const startDay = daysBetween(projectStart, taskStart);
    const duration = daysBetween(taskStart, taskEnd) + 1;
    
    const leftPercent = (startDay / totalDays) * 100;
    const widthPercent = (duration / totalDays) * 100;
    
    return { left: leftPercent, width: widthPercent };
}

function renderTaskRow(task, index) {
    const employee = employees.find(e => e.id === task.assignee);
    const timelineStatus = calculateTimelineStatus(task);
    const pos = calculateGanttPosition(task.startDate, task.endDate);
    
    const timelineStatusText = {
        'late': 'Terlambat',
        'ontime': 'Tepat Waktu',
        'early': 'Sebelum Deadline'
    };
    
    const statusLabels = {
        'todo': 'To Do',
        'progress': 'In Progress',
        'review': 'Review',
        'done': 'Done'
    };
    
    // Generate level options
    const levelOptions = [1, 2, 3, 4, 5].map(level => `
        <div class="level-option" data-level="${level}" onclick="changeLevel(${task.id}, '${level}')">
            <div class="level-dot"></div>
            <span>Level ${level}</span>
        </div>
    `).join('');
    
    return `
        <tr data-task-id="${task.id}">
            <td style="text-align: center;">
                <div class="task-number">${index + 1}</div>
            </td>
            
            <td>
                <div class="action-cell">
                    <button class="action-btn detail" onclick="showTaskDetail(${task.id})" title="Detail">
                        <i class="bx bx-show"></i>
                    </button>
                    <button class="action-btn delete" onclick="deleteTask(${task.id})" title="Hapus">
                        <i class="bx bx-trash"></i>
                    </button>
                </div>
            </td>
            
            <td class="task-info-cell">
                <input type="text" class="task-name-input" value="${task.nama}" 
                    placeholder="Nama task..." 
                    onchange="updateTask(${task.id}, 'nama', this.value)">
                <textarea class="task-desc-input" rows="2" 
                    placeholder="Deskripsi task..." 
                    onchange="updateTask(${task.id}, 'desc', this.value)">${task.desc}</textarea>
            </td>
            
            <td>
                <select class="compact-select assignee-select" onchange="updateTask(${task.id}, 'assignee', parseInt(this.value))">
                    ${employees.map(emp => `
                        <option value="${emp.id}" ${emp.id === task.assignee ? 'selected' : ''}>
                            ${emp.name}
                        </option>
                    `).join('')}
                </select>
            </td>
            
            <!-- PERBAIKAN: Status & Level dalam 1 kolom, berdampingan -->
            <td class="status-level-cell">
                <div class="status-level-container">
                    <!-- Baris 1: Select Status -->
                    <div class="status-select-row">
                        <select class="compact-select" 
                            onchange="updateTask(${task.id}, 'status', this.value); renderAllTasks();">
                            <option value="todo" ${task.status === 'todo' ? 'selected' : ''}>To Do</option>
                            <option value="progress" ${task.status === 'progress' ? 'selected' : ''}>In Progress</option>
                            <option value="review" ${task.status === 'review' ? 'selected' : ''}>Review</option>
                            <option value="done" ${task.status === 'done' ? 'selected' : ''}>Done</option>
                        </select>
                    </div>
                    
                    <!-- Baris 2: Status Badge dan Level Button BERDAMPINGAN -->
                    <div class="status-level-row">
                        <span class="status-badge status-${task.status}">
                            ${statusLabels[task.status]}
                        </span>
                        
                        <div class="level-select-container">
                            <div class="level-display level-${task.level}" onclick="toggleLevelDropdown(this.parentElement)">
                                Level ${task.level}
                            </div>
                            <div class="level-dropdown">
                                ${levelOptions}
                            </div>
                        </div>
                    </div>
                </div>
            </td>
            
            <td class="date-cell">
                <div class="date-info">
                    <div class="date-row">
                        <div class="date-label">Deadline</div>
                        <div class="date-value">${formatFullDate(task.endDate)}</div>
                    </div>
                    <span class="timeline-status timeline-${timelineStatus}">
                        ${timelineStatusText[timelineStatus]}
                    </span>
                </div>
            </td>
            
            <td>
                <div class="gantt-container" id="gantt-container-${task.id}">
                    <div class="gantt-timeline">
                        <div class="gantt-month">
                            <div class="gantt-month-label">Januari 2026</div>
                        </div>
                        <div class="gantt-month">
                            <div class="gantt-month-label">Februari 2026</div>
                        </div>
                        <div class="gantt-month">
                            <div class="gantt-month-label">Maret 2026</div>
                        </div>
                    </div>
                    
                    <div class="gantt-bar-container" 
                         style="left: ${pos.left}%; width: ${pos.width}%;"
                         data-task-id="${task.id}"
                         onmousedown="startDrag(event, ${task.id})">
                        <div class="gantt-bar ${task.status}">
                            <span>${formatDate(task.startDate)}</span>
                            <span>${formatDate(task.endDate)}</span>
                            <div class="resize-handle left" onmousedown="startResize(event, ${task.id}, 'left')"></div>
                            <div class="resize-handle right" onmousedown="startResize(event, ${task.id}, 'right')"></div>
                        </div>
                    </div>
                </div>
            </td>
        </tr>
    `;
}

function renderAllTasks() {
    const taskBody = document.getElementById('taskBody');
    const emptyState = document.getElementById('emptyState');
    
    if (tasks.length === 0) {
        taskBody.innerHTML = '';
        emptyState.style.display = 'block';
    } else {
        emptyState.style.display = 'none';
        taskBody.innerHTML = tasks.map((task, index) => renderTaskRow(task, index)).join('');
    }
    
    updateStatistics();
    updateCharts();
}

function addNewTask() {
    const today = new Date();
    const nextWeek = new Date(today.getTime() + 7 * 24 * 60 * 60 * 1000);
    
    const newTask = {
        id: taskIdCounter++,
        nama: 'Task Baru',
        desc: 'Deskripsi task baru',
        assignee: employees[0].id,
        status: 'todo',
        level: '2',
        startDate: today.toISOString().split('T')[0],
        endDate: nextWeek.toISOString().split('T')[0],
        progress: 0
    };
    
    tasks.push(newTask);
    renderAllTasks();
    
    setTimeout(() => {
        const row = document.querySelector(`[data-task-id="${newTask.id}"]`);
        if (row) {
            row.scrollIntoView({ behavior: 'smooth', block: 'center' });
            const input = row.querySelector('.task-name-input');
            if (input) {
                input.focus();
                input.select();
            }
        }
    }, 100);
}

function updateTask(taskId, field, value) {
    const task = tasks.find(t => t.id === taskId);
    if (task) {
        task[field] = value;
        updateStatistics();
        updateCharts();
    }
}

function startDrag(e, taskId) {
    if (e.target.classList.contains('resize-handle')) return;
    
    e.preventDefault();
    e.stopPropagation();
    
    draggedElement = e.target.closest('.gantt-bar-container');
    if (!draggedElement) return;
    
    dragStartX = e.clientX;
    const rect = draggedElement.getBoundingClientRect();
    const parent = draggedElement.parentElement.getBoundingClientRect();
    dragStartLeft = ((rect.left - parent.left) / parent.width) * 100;
    
    draggedElement.style.opacity = '0.7';
    draggedElement.style.zIndex = '100';
    
    document.addEventListener('mousemove', onDrag);
    document.addEventListener('mouseup', stopDrag);
}

function onDrag(e) {
    if (!draggedElement) return;
    
    const parent = draggedElement.parentElement.getBoundingClientRect();
    const moveX = e.clientX - dragStartX;
    const movePercent = (moveX / parent.width) * 100;
    const newLeft = Math.max(0, Math.min(100 - parseFloat(draggedElement.style.width), dragStartLeft + movePercent));
    
    draggedElement.style.left = newLeft + '%';
}

function stopDrag(e) {
    if (!draggedElement) return;
    
    draggedElement.style.opacity = '';
    draggedElement.style.zIndex = '';
    
    const taskId = parseInt(draggedElement.dataset.taskId);
    updateTaskDates(taskId);
    
    document.removeEventListener('mousemove', onDrag);
    document.removeEventListener('mouseup', stopDrag);
    draggedElement = null;
}

function startResize(e, taskId, direction) {
    e.preventDefault();
    e.stopPropagation();
    
    isResizing = true;
    resizeDirection = direction;
    draggedElement = document.querySelector(`[data-task-id="${taskId}"].gantt-bar-container`);
    
    if (!draggedElement) return;
    
    dragStartX = e.clientX;
    dragStartLeft = parseFloat(draggedElement.style.left);
    
    draggedElement.style.opacity = '0.7';
    
    document.addEventListener('mousemove', onResize);
    document.addEventListener('mouseup', stopResize);
}

function onResize(e) {
    if (!isResizing || !draggedElement) return;
    
    const parent = draggedElement.parentElement.getBoundingClientRect();
    const moveX = e.clientX - dragStartX;
    const movePercent = (moveX / parent.width) * 100;
    
    const currentLeft = parseFloat(draggedElement.style.left);
    const currentWidth = parseFloat(draggedElement.style.width);
    
    if (resizeDirection === 'left') {
        const newLeft = Math.max(0, Math.min(currentLeft + currentWidth - 5, dragStartLeft + movePercent));
        const newWidth = Math.max(5, currentLeft + currentWidth - newLeft);
        draggedElement.style.left = newLeft + '%';
        draggedElement.style.width = newWidth + '%';
    } else if (resizeDirection === 'right') {
        const newWidth = Math.max(5, Math.min(100 - currentLeft, currentWidth + movePercent));
        draggedElement.style.width = newWidth + '%';
    }
}

function stopResize(e) {
    if (!isResizing || !draggedElement) return;
    
    draggedElement.style.opacity = '';
    
    const taskId = parseInt(draggedElement.dataset.taskId);
    updateTaskDates(taskId);
    
    document.removeEventListener('mousemove', onResize);
    document.removeEventListener('mouseup', stopResize);
    
    isResizing = false;
    resizeDirection = null;
    draggedElement = null;
}

function updateTaskDates(taskId) {
    const task = tasks.find(t => t.id === taskId);
    const element = document.querySelector(`[data-task-id="${taskId}"].gantt-bar-container`);
    
    if (task && element) {
        const leftPercent = parseFloat(element.style.left);
        const widthPercent = parseFloat(element.style.width);
        
        const totalDays = daysBetween(projectStart, projectEnd);
        const newStartDay = Math.round((leftPercent / 100) * totalDays);
        const duration = Math.round((widthPercent / 100) * totalDays);
        
        const newStart = new Date(projectStart);
        newStart.setDate(newStart.getDate() + newStartDay);
        const newEnd = new Date(newStart);
        newEnd.setDate(newEnd.getDate() + duration - 1);
        
        task.startDate = newStart.toISOString().split('T')[0];
        task.endDate = newEnd.toISOString().split('T')[0];
        
        renderAllTasks();
    }
}

function showTaskDetail(taskId) {
    const task = tasks.find(t => t.id === taskId);
    if (!task) return;
    
    const employee = employees.find(e => e.id === task.assignee);
    const timelineStatus = calculateTimelineStatus(task);
    const duration = daysBetween(task.startDate, task.endDate) + 1;
    
    document.getElementById('detailTaskName').textContent = task.nama;
    document.getElementById('detailTaskId').textContent = `ID: ${task.id}`;
    document.getElementById('detailTaskDesc').textContent = task.desc;
    document.getElementById('detailTaskAssignee').textContent = employee.name;
    document.getElementById('detailStartDate').textContent = formatFullDate(task.startDate);
    document.getElementById('detailEndDate').textContent = formatFullDate(task.endDate);
    document.getElementById('detailDuration').textContent = `${duration} hari`;
    
    const statusLabels = {
        'todo': 'To Do',
        'progress': 'In Progress',
        'review': 'Review',
        'done': 'Done'
    };
    
    document.getElementById('detailTaskStatusLevel').innerHTML = `
        <div style="display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
            <span class="status-badge status-${task.status}">
                ${statusLabels[task.status]}
            </span>
            <span class="level-badge level-${task.level}">Level ${task.level}</span>
        </div>
    `;
    
    const timelineStatusLabels = {
        'late': 'Terlambat',
        'ontime': 'Tepat Waktu',
        'early': 'Sebelum Deadline'
    };
    
    document.getElementById('detailTimelineStatus').innerHTML = `
        <span class="timeline-status timeline-${timelineStatus}">
            ${timelineStatusLabels[timelineStatus]}
        </span>
    `;
    
    const progressBar = document.getElementById('detailProgressBar');
    progressBar.style.width = `${task.progress}%`;
    progressBar.className = `progress-bar ${task.status === 'done' ? 'bg-success' : task.status === 'progress' ? 'bg-warning' : 'bg-secondary'}`;
    
    const modal = new bootstrap.Modal(document.getElementById('taskDetailModal'));
    modal.show();
}

function deleteTask(taskId) {
    if (confirm('Apakah Anda yakin ingin menghapus task ini?')) {
        tasks = tasks.filter(t => t.id !== taskId);
        renderAllTasks();
        showNotification('Task berhasil dihapus', 'success');
    }
}

function updateStatistics() {
    const total = tasks.length;
    const done = tasks.filter(t => t.status === 'done').length;
    const progress = tasks.filter(t => t.status === 'progress' || t.status === 'review').length;
    const todo = tasks.filter(t => t.status === 'todo').length;
    
    const donePercentage = total > 0 ? Math.round((done / total) * 100) : 0;
    const progressPercentage = total > 0 ? Math.round((progress / total) * 100) : 0;
    const todoPercentage = total > 0 ? Math.round((todo / total) * 100) : 0;
    
    document.getElementById('totalTasks').textContent = total;
    document.getElementById('doneTasks').textContent = done;
    document.getElementById('progressTasks').textContent = progress;
    document.getElementById('todoTasks').textContent = todo;
    
    document.getElementById('donePercentage').textContent = `${donePercentage}% dari total`;
    document.getElementById('progressPercentage').textContent = `${progressPercentage}% dari total`;
    document.getElementById('todoPercentage').textContent = `${todoPercentage}% dari total`;
}

function saveAllTasks() {
    console.log('Saving tasks:', tasks);
    showNotification(`${tasks.length} task berhasil disimpan`, 'success');
}

function exportTasks() {
    const data = {
        project: 'E-Commerce Redesign',
        exportDate: new Date().toISOString(),
        tasks: tasks
    };
    
    const dataStr = JSON.stringify(data, null, 2);
    const dataUri = 'data:application/json;charset=utf-8,' + encodeURIComponent(dataStr);
    
    const exportFileDefaultName = `tasks-export-${new Date().toISOString().split('T')[0]}.json`;
    
    const linkElement = document.createElement('a');
    linkElement.setAttribute('href', dataUri);
    linkElement.setAttribute('download', exportFileDefaultName);
    linkElement.click();
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        border-radius: 8px;
    `;
    
    notification.innerHTML = `
        <strong>${type === 'success' ? 'Sukses!' : 'Error!'}</strong> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}

function updateCharts() {
    const total = tasks.length;
    const done = tasks.filter(t => t.status === 'done').length;
    const donePercentage = total > 0 ? Math.round((done / total) * 100) : 0;
    
    document.getElementById('progressPercentageText').textContent = `${donePercentage}%`;
    
    // Update progress chart
    if (progressChart) {
        progressChart.updateSeries([donePercentage]);
    }
    
    // Update performance chart
    updateEmployeePerformanceChart(currentChartView);
}

document.addEventListener('DOMContentLoaded', function() {
    renderAllTasks();
    
    // Initialize progress chart
    const progressElement = document.querySelector('#progressPieChart');
    if (progressElement) {
        const total = tasks.length;
        const done = tasks.filter(t => t.status === 'done').length;
        const donePercentage = total > 0 ? Math.round((done / total) * 100) : 0;
        
        progressChart = new ApexCharts(progressElement, {
            chart: {
                type: 'radialBar',
                height: 200,
                sparkline: { enabled: true }
            },
            series: [donePercentage],
            colors: ['#10B981'],
            plotOptions: {
                radialBar: {
                    startAngle: -90,
                    endAngle: 90,
                    hollow: {
                        size: '70%',
                        background: 'transparent'
                    },
                    track: {
                        background: '#F3F4F6',
                        strokeWidth: '100%'
                    },
                    dataLabels: {
                        show: false
                    }
                }
            },
            stroke: {
                lineCap: 'round'
            }
        });
        
        progressChart.render();
    }
    
    // Initialize performance chart
    updateEmployeePerformanceChart('bar');
});
</script>
@endpush