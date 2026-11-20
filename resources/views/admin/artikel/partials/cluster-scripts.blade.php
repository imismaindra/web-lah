<script>
    document.addEventListener('DOMContentLoaded', function () {
        const inputIsPillar = document.getElementById('input_is_pillar');
        const selectPillar = document.getElementById('pillar_id');
        const pillarContainer = document.getElementById('pillar-selector-container');
        const statusBox = document.getElementById('pillar-backlink-status');
        const detectedCount = document.getElementById('detected-links-count');

        const modal = document.getElementById('smart-link-modal');
        const modalCard = document.getElementById('smart-link-modal-card');
        const searchInput = document.getElementById('smart-link-search-input');
        const resultsList = document.getElementById('smart-link-results-list');
        const selectedTextDisplay = document.getElementById('smart-link-selected-text');
        const pillarQuickPin = document.getElementById('smart-link-pillar-quick-pin');
        const pillarTitleDisplay = document.getElementById('smart-link-pillar-title');
        const btnPillarInsert = document.getElementById('btn-smart-link-pillar-insert');

        let lastSelectionRange = null;
        let selectedAnchorText = '';
        let searchDebounceTimer = null;

        const currentArticleId = '{{ isset($artikel) ? $artikel->id : "" }}';

        // 1. Cluster Role Switcher
        document.querySelectorAll('input[name="cluster_role"]').forEach(radio => {
            radio.addEventListener('change', function () {
                const role = this.value;
                if (role === 'pilar') {
                    inputIsPillar.value = '1';
                    selectPillar.value = '';
                    pillarContainer.classList.add('hidden');
                } else if (role === 'pendukung') {
                    inputIsPillar.value = '0';
                    pillarContainer.classList.remove('hidden');
                } else {
                    inputIsPillar.value = '0';
                    selectPillar.value = '';
                    pillarContainer.classList.add('hidden');
                }
                checkInternalLinks();
            });
        });

        selectPillar?.addEventListener('change', checkInternalLinks);

        // 2. Real-Time Link Checker
        function checkInternalLinks() {
            if (typeof quill === 'undefined') return;

            const html = quill.root.innerHTML || '';
            const links = html.match(/<a\s+[^>]*href=["']([^"']+)["'][^>]*>/gi) || [];
            if (detectedCount) {
                detectedCount.textContent = links.length + ' tautan';
            }

            const currentRole = document.querySelector('input[name="cluster_role"]:checked')?.value || 'standar';
            if (currentRole !== 'pendukung' || !selectPillar || !selectPillar.value) {
                if (statusBox) {
                    statusBox.innerHTML = '<span class="text-stone-400 text-[11px]">Pilih pilar induk untuk memverifikasi tautan balik.</span>';
                    statusBox.className = 'rounded-xl border border-stone-200 bg-stone-50/50 p-2.5 text-xs dark:border-white/10 dark:bg-white/[0.02]';
                }
                return;
            }

            const opt = selectPillar.options[selectPillar.selectedIndex];
            const pillarSlug = opt?.dataset?.slug || '';
            const pillarTitle = opt?.dataset?.title || opt?.text || 'Pilar';

            const regex = new RegExp('href=["\'][^"\']*artikel/' + pillarSlug.replace(/[-/\\^$*+?.()|[\]{}]/g, '\\$&') + '["\']', 'i');
            const hasBacklink = regex.test(html);

            if (hasBacklink) {
                statusBox.className = 'rounded-xl border border-emerald-300 bg-emerald-50/70 p-3 text-xs dark:border-emerald-500/20 dark:bg-emerald-950/20';
                statusBox.innerHTML = `
                    <div class="flex items-center gap-2 text-emerald-800 dark:text-emerald-300 font-semibold">
                        <svg class="h-4 w-4 flex-shrink-0 text-emerald-600 dark:text-emerald-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6 9 17l-5-5"/></svg>
                        <span>Tautan Balik ke Pilar Aman</span>
                    </div>
                    <p class="text-[11px] text-emerald-700/80 dark:text-emerald-400/80 mt-1">Artikel ini telah menautkan ke pilar "${pillarTitle}".</p>
                `;
            } else {
                statusBox.className = 'rounded-xl border border-amber-300 bg-amber-50/70 p-3 text-xs dark:border-amber-500/20 dark:bg-amber-950/25';
                statusBox.innerHTML = `
                    <div class="flex items-center gap-2 text-amber-900 dark:text-amber-200 font-bold">
                        <svg class="h-4 w-4 flex-shrink-0 text-amber-600 dark:text-amber-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                        <span>Belum Menautkan Balik ke Pilar!</span>
                    </div>
                    <p class="text-[11px] text-amber-800/80 dark:text-amber-300/80 mt-1">Agar kluster SEO bekerja, artikel turunan wajib memiliki tautan balik ke artikel pilar.</p>
                    <button type="button" id="btn-quick-insert-pillar" class="mt-2.5 inline-flex items-center gap-1.5 rounded-lg bg-[#1e3a5f] px-3 py-1.5 text-xs font-semibold text-white shadow-2xs transition hover:bg-[#16304a] active:scale-[0.98] dark:bg-white dark:text-stone-900 dark:hover:bg-stone-200">
                        <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                        <span>Sisipkan Tautan Pilar ke Editor</span>
                    </button>
                `;

                document.getElementById('btn-quick-insert-pillar')?.addEventListener('click', function () {
                    insertInternalLink('/artikel/' + pillarSlug, pillarTitle);
                });
            }
        }

        // 3. Smart Link Modal Control
        function openSmartLinkModal() {
            if (typeof quill === 'undefined') return;

            lastSelectionRange = quill.getSelection(true);
            if (lastSelectionRange && lastSelectionRange.length > 0) {
                selectedAnchorText = quill.getText(lastSelectionRange.index, lastSelectionRange.length).trim();
                selectedTextDisplay.textContent = `"${selectedAnchorText}"`;
            } else {
                selectedAnchorText = '';
                selectedTextDisplay.textContent = '(Belum memilih teks, judul artikel akan disisipkan)';
            }

            // Quick Pillar Button
            const selectedPillarOpt = selectPillar?.options[selectPillar.selectedIndex];
            if (selectPillar && selectPillar.value && selectedPillarOpt) {
                pillarTitleDisplay.textContent = selectedPillarOpt.dataset.title || selectedPillarOpt.text;
                pillarQuickPin.classList.remove('hidden');
                btnPillarInsert.onclick = function () {
                    insertInternalLink('/artikel/' + selectedPillarOpt.dataset.slug, selectedPillarOpt.dataset.title || selectedPillarOpt.text);
                    closeSmartLinkModal();
                };
            } else {
                pillarQuickPin.classList.add('hidden');
            }

            modal.classList.remove('opacity-0', 'pointer-events-none');
            modalCard.classList.remove('scale-95');
            searchInput.value = selectedAnchorText;
            fetchLinkCandidates(selectedAnchorText);
            setTimeout(() => searchInput.focus(), 50);
        }

        function closeSmartLinkModal() {
            modalCard.classList.add('scale-95');
            modal.classList.add('opacity-0', 'pointer-events-none');
            if (typeof quill !== 'undefined') {
                quill.focus();
            }
        }

        function insertInternalLink(targetUrl, fallbackTitle) {
            if (typeof quill === 'undefined') return;

            const range = lastSelectionRange || quill.getSelection(true) || { index: quill.getLength() - 1, length: 0 };
            const anchor = selectedAnchorText || fallbackTitle;

            if (range.length > 0) {
                quill.formatText(range.index, range.length, 'link', targetUrl);
            } else {
                quill.insertText(range.index, anchor, 'link', targetUrl);
                quill.setSelection(range.index + anchor.length, 0);
            }

            const kontenInput = document.getElementById('konten');
            if (kontenInput) {
                kontenInput.value = quill.root.innerHTML;
            }

            checkInternalLinks();
        }

        function fetchLinkCandidates(q = '') {
            const pillarId = selectPillar?.value || '';
            const url = new URL('{{ route("admin.artikel.search-links") }}', window.location.origin);
            if (q) url.searchParams.append('q', q);
            if (currentArticleId) url.searchParams.append('current_id', currentArticleId);
            if (pillarId) url.searchParams.append('pillar_id', pillarId);

            resultsList.innerHTML = '<div class="py-4 text-center text-xs text-stone-400">Mencari artikel...</div>';

            fetch(url)
                .then(r => r.json())
                .then(data => {
                    const results = data.results || [];
                    if (!results.length) {
                        resultsList.innerHTML = '<div class="py-6 text-center text-xs text-stone-400">Tidak ada artikel yang cocok.</div>';
                        return;
                    }

                    resultsList.innerHTML = '';
                    results.forEach(art => {
                        const item = document.createElement('div');
                        item.className = 'group flex items-center justify-between rounded-xl border border-stone-100 p-2.5 transition hover:border-stone-300 hover:bg-stone-50 dark:border-white/[0.04] dark:hover:border-white/10 dark:hover:bg-white/[0.03] cursor-pointer';
                        item.innerHTML = `
                            <div class="min-w-0 pr-2">
                                <div class="flex items-center gap-1.5">
                                    ${art.is_pillar ? '<span class="rounded bg-[#1e3a5f]/10 px-1.5 py-0.2 text-[9px] font-bold text-[#1e3a5f] uppercase dark:bg-sky-400/10 dark:text-sky-300">Pilar</span>' : ''}
                                    <span class="font-serif text-xs font-bold text-stone-800 line-clamp-1 group-hover:text-[#1e3a5f] dark:text-stone-200 dark:group-hover:text-white">${art.title}</span>
                                </div>
                                <div class="mt-0.5 text-[10px] text-stone-400 flex items-center gap-2">
                                    <span>${art.category}</span>
                                    <span>&bull;</span>
                                    <span class="font-mono">/artikel/${art.slug}</span>
                                </div>
                            </div>
                            <button type="button" class="flex-shrink-0 rounded-lg bg-stone-100 px-2.5 py-1 text-xs font-semibold text-stone-700 transition hover:bg-[#1e3a5f] hover:text-white dark:bg-white/[0.06] dark:text-stone-300 dark:hover:bg-white dark:hover:text-stone-900">
                                Pilih
                            </button>
                        `;

                        item.addEventListener('click', () => {
                            insertInternalLink('/artikel/' + art.slug, art.title);
                            closeSmartLinkModal();
                        });

                        resultsList.appendChild(item);
                    });
                })
                .catch(() => {
                    resultsList.innerHTML = '<div class="py-4 text-center text-xs text-red-500">Gagal memuat artikel.</div>';
                });
        }

        // Live Search Input debounce
        searchInput?.addEventListener('input', function () {
            clearTimeout(searchDebounceTimer);
            searchDebounceTimer = setTimeout(() => {
                fetchLinkCandidates(this.value.trim());
            }, 250);
        });

        document.getElementById('btn-open-smart-link-modal')?.addEventListener('click', openSmartLinkModal);
        document.getElementById('btn-close-smart-link-modal')?.addEventListener('click', closeSmartLinkModal);
        modal?.addEventListener('click', function (e) {
            if (e.target === modal) closeSmartLinkModal();
        });

        // 4. Keyboard Shortcuts: Ctrl+K / Cmd+K and Esc
        window.addEventListener('keydown', function (e) {
            if ((e.ctrlKey || e.metaKey) && e.key.toLowerCase() === 'k') {
                e.preventDefault();
                openSmartLinkModal();
            }
            if (e.key === 'Escape' && !modal.classList.contains('pointer-events-none')) {
                closeSmartLinkModal();
            }
        });

        // 5. Inject Quill Toolbar Button for Smart Link
        const qlToolbar = document.querySelector('.ql-toolbar');
        if (qlToolbar) {
            const fmt = document.createElement('span');
            fmt.className = 'ql-formats';
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.title = 'Tautkan Artikel Internal (Ctrl+K)';
            btn.innerHTML = `<svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>`;
            btn.addEventListener('click', openSmartLinkModal);
            fmt.appendChild(btn);
            qlToolbar.appendChild(fmt);
        }

        // 6. Listen to Quill text changes for real-time validation
        if (typeof quill !== 'undefined') {
            quill.on('text-change', function () {
                checkInternalLinks();
            });
        }

        // Initial check
        setTimeout(checkInternalLinks, 300);
    });
</script>
