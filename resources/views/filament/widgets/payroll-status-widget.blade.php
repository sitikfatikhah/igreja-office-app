<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            Ringkasan Status Penggajian
        </x-slot>
        <x-slot name="description">
            Gambaran umum status penggajian.
        </x-slot>

        <div class="payroll-stats-widget" style="display: grid; grid-template-columns: 180px 1fr; gap: 24px; align-items: center;">

            <div style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                <svg width="148" height="148" viewBox="0 0 160 160">
                    <circle cx="80" cy="80" r="68" fill="none" stroke="#e5e7eb" stroke-width="14" class="psw-track"/>
                    <circle
                        cx="80" cy="80" r="68" fill="none"
                        stroke="#6366f1"
                        stroke-width="14"
                        stroke-dasharray="427.3"
                        stroke-dashoffset="{{ round(427.3 * (1 - $paidRate / 100), 2) }}"
                        stroke-linecap="round"
                        transform="rotate(-90 80 80)"
                    />
                    <text x="80" y="74" text-anchor="middle" font-size="26" font-weight="600" class="psw-text-strong">
                        {{ $paidRate }}%
                    </text>
                    <text x="80" y="96" text-anchor="middle" font-size="11" class="psw-text-muted">
                        dibayar
                    </text>
                </svg>
                <p class="psw-text-muted" style="font-size: 12px; text-align: center; margin-top: 4px;">
                    Payroll value<br>
                    <span class="psw-text-strong" style="font-weight: 600;">Rp {{ number_format($payrollValue, 0, ',', '.') }}</span>
                </p>
            </div>

            <div style="display: flex; flex-direction: column; gap: 12px;">

                <div>
                    <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 4px;">
                        <span class="psw-text-muted" style="display: inline-flex; align-items: center; gap: 4px;">
                            <x-heroicon-o-clock style="width: 16px; height: 16px; color: #f59e0b;" />
                            Draf
                        </span>
                        <span class="psw-text-strong" style="font-weight: 600;">{{ number_format($draft) }}</span>
                    </div>
                    <div class="psw-track" style="height: 8px; border-radius: 4px; overflow: hidden;">
                        <div style="height: 100%; width: {{ $draftPercent }}%; background: #f59e0b; border-radius: 4px;"></div>
                    </div>
                </div>

                <div>
                    <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 4px;">
                        <span class="psw-text-muted" style="display: inline-flex; align-items: center; gap: 4px;">
                            <x-heroicon-o-document-check style="width: 16px; height: 16px; color: #3b82f6;" />
                            Dibuat
                        </span>
                        <span class="psw-text-strong" style="font-weight: 600;">{{ number_format($generated) }}</span>
                    </div>
                    <div class="psw-track" style="height: 8px; border-radius: 4px; overflow: hidden;">
                        <div style="height: 100%; width: {{ $generatedPercent }}%; background: #3b82f6; border-radius: 4px;"></div>
                    </div>
                </div>

                <div>
                    <div style="display: flex; justify-content: space-between; font-size: 13px; margin-bottom: 4px;">
                        <span class="psw-text-muted" style="display: inline-flex; align-items: center; gap: 4px;">
                            <x-heroicon-o-check-circle style="width: 16px; height: 16px; color: #10b981;" />
                            Dibayar
                        </span>
                        <span class="psw-text-strong" style="font-weight: 600;">{{ number_format($paid) }}</span>
                    </div>
                    <div class="psw-track" style="height: 8px; border-radius: 4px; overflow: hidden;">
                        <div style="height: 100%; width: {{ $paidPercent }}%; background: #10b981; border-radius: 4px;"></div>
                    </div>
                </div>

                <div class="psw-divider" style="display: flex; gap: 32px; margin-top: 4px; padding-top: 12px;">
                    <div>
                        <div class="psw-text-strong" style="font-size: 20px; font-weight: 600;">{{ number_format($totalPayrolls) }}</div>
                        <div class="psw-text-muted" style="font-size: 12px;">total payrolls</div>
                    </div>
                    <div>
                        <div class="psw-text-strong" style="font-size: 13px; font-weight: 600; margin-top: 2px;">{{ $payrollValueTrend }}</div>
                        <div class="psw-text-muted" style="font-size: 12px;">tren nilai dibayar</div>
                    </div>
                </div>

            </div>
        </div>

        <style>
            .payroll-stats-widget .psw-text-strong { color: #0f172a; }
            .payroll-stats-widget .psw-text-muted { color: #64748b; }
            .payroll-stats-widget .psw-track { background: #e5e7eb; }
            .payroll-stats-widget .psw-divider { border-top: 1px solid #e5e7eb; }

            html.dark .payroll-stats-widget .psw-text-strong { color: #f8fafc; }
            html.dark .payroll-stats-widget .psw-text-muted { color: #94a3b8; }
            html.dark .payroll-stats-widget .psw-track { background: rgba(255, 255, 255, 0.1); }
            html.dark .payroll-stats-widget .psw-divider { border-top: 1px solid rgba(255, 255, 255, 0.1); }

            @media (max-width: 640px) {
                .payroll-stats-widget {
                    grid-template-columns: 1fr !important;
                }
            }
        </style>
    </x-filament::section>
</x-filament-widgets::widget>