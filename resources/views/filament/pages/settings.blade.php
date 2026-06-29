<x-filament-panels::page>
    <div class="space-y-6">
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-950 dark:text-white">System Configuration</h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Manage attendance, leave, payroll, notification, and security settings for your office operations.
                    </p>
                </div>
                <div class="rounded-xl bg-primary-50 px-4 py-3 text-sm text-primary-700 dark:bg-primary-500/10 dark:text-primary-300">
                    Changes are applied immediately after saving.
                </div>
            </div>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-900">
            {{ $this->form }}
        </div>
    </div>
</x-filament-panels::page>
