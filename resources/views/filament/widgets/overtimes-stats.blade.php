<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Analitik Lembur
        </x-slot>
        <x-slot name="description">
            Gambaran umum analitik lembur.
        </x-slot>

        <div class="overtime-stats-widget" style="display: grid; grid-template-columns: 180px 1fr; gap: 24px; align-items: center;">

            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                <svg width="148" height="148" viewBox="0 0 160 160">
                    <circle cx="80" cy="80" r="68" fill="none" stroke="#e5e7eb" stroke-width="14" class="osw-track"/>
                    <circle
                        cx="80" cy="80" r="68" fill="none"
                        stroke="#3b82f6"
                        stroke-width="14"
                        stroke-dasharray="427.3"
                        stroke-dashoffset="{{ round(427.3 * (1 - $approvalRate / 100), 2) }}"
                        stroke-linecap="round"
                        transform="rotate(-90 80 80)"
                    />
                    <text x="80" y="74" text-anchor="middle" font-size="26" font-weight="600" class="osw-text-strong">
                        {{ $approvalRate }}%
                    </text>
                    <text x="80" y="96" text-anchor="middle" font-size="11" class="osw-text-muted">
                        disetujui
                    </text>
                </svg>
                <p class="osw-text-muted" style="font-size: 12px; text-align: center; margin-top: 4px;">
                    Tingkat persetujuan<br>
                    <span class="osw-text-strong" style="font-weight: 600;">{{ $approvalRateTrend }}</span>
                </p>
            </div>

            <div style="display: flex; flex-direction: column; gap: 12px;">

                <div>
                    <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 4px;">
                        <span class="osw-text-muted" style="display: inline-flex; align-items: center; gap: 4px;">
                            <x-heroicon-o-clock style="width: 16px; height: 16px; color: #f59e0b;" />
                            Menunggu
                        </span>
                        <span class="osw-text-strong" style="font-weight: 600;">{{ number_format($pendingRequests) }} permohonan</span>
                    </div>
                    <div class="osw-track" style="height: 8px; border-radius: 4px; overflow: hidden;">
                        <div style="height: 100%; width: {{ $pendingPercent }}%; background: #f59e0b; border-radius: 4px;"></div>
                    </div>
                </div>

                <div>
                    <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 4px;">
                        <span class="osw-text-muted" style="display: inline-flex; align-items: center; gap: 4px;">
                            <x-heroicon-o-check-circle style="width: 16px; height: 16px; color: #10b981;" />
                            Disetujui
                        </span>
                        <span class="osw-text-strong" style="font-weight: 600;">{{ number_format($approvedRequests) }} permohonan</span>
                    </div>
                    <div class="osw-track" style="height: 8px; border-radius: 4px; overflow: hidden;">
                        <div style="height: 100%; width: {{ $approvedPercent }}%; background: #10b981; border-radius: 4px;"></div>
                    </div>
                </div>

                <div class="osw-divider" style="display: flex; gap: 32px; margin-top: 4px; padding-top: 12px;">
                    <div>
                        <div class="osw-text-strong" style="font-size: 20px; font-weight: 600;">{{ number_format($totalRequests) }}</div>
                        <div class="osw-text-muted" style="font-size: 12px;">total permohonan</div>
                    </div>
                    <div>
                        <div class="osw-text-strong" style="font-size: 20px; font-weight: 600;">{{ number_format($totalOvertimeHours) }}</div>
                        <div class="osw-text-muted" style="font-size: 12px;">jam lembur</div>
                    </div>
                </div>

            </div>
        </div>

        <style>
            .overtime-stats-widget .osw-text-strong { color: #0f172a; }
            .overtime-stats-widget .osw-text-muted { color: #64748b; }
            .overtime-stats-widget .osw-track { background: #e5e7eb; }
            .overtime-stats-widget .osw-divider { border-top: 1px solid #e5e7eb; }

            html.dark .overtime-stats-widget .osw-text-strong { color: #f8fafc; }
            html.dark .overtime-stats-widget .osw-text-muted { color: #94a3b8; }
            html.dark .overtime-stats-widget .osw-track { background: rgba(255, 255, 255, 0.1); }
            html.dark .overtime-stats-widget .osw-divider { border-top: 1px solid rgba(255, 255, 255, 0.1); }

            @media (max-width: 640px) {
                .overtime-stats-widget {
                    grid-template-columns: 1fr !important;
                }
            }
        </style>
    </x-filament::section>
</x-filament-widgets::widget>