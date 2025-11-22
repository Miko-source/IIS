<script>
    (function() {
        if (window.initCampaignForm) return;

        window.initCampaignForm = function(config) {
            const {
                formId,
                nameId,
                nameErrorId,
                startDisplayId,
                startHiddenId,
                startErrorId,
                endDisplayId,
                endHiddenId,
                endErrorId,
            } = config;

            const form = document.getElementById(formId);
            if (!form) return;

            const nameInput = document.getElementById(nameId);
            const nameError = document.getElementById(nameErrorId);
            const startDisplay = document.getElementById(startDisplayId);
            const startHidden = document.getElementById(startHiddenId);
            const startError = document.getElementById(startErrorId);
            const endDisplay = document.getElementById(endDisplayId);
            const endHidden = document.getElementById(endHiddenId);
            const endError = document.getElementById(endErrorId);

            if (!nameInput || !nameError || !startDisplay || !startHidden || !startError || !endDisplay || !endHidden || !endError) {
                return;
            }

            // --- TVRDÝ RESET CACHE ---
            // Okamžitě vymažeme vizuální inputy, aby prohlížeč nemohl nic "předvyplnit" špatně.
            // Správná data tam za milisekundu vrátíme ze serverových (hidden) dat.
            startDisplay.value = '';
            endDisplay.value = '';
            // -------------------------

            function convertToISO(dateStr) {
                if (!dateStr || dateStr.trim() === '') return '';
                const parts = dateStr.split('/');
                if (parts.length !== 3) return '';
                const day = parts[0].padStart(2, '0');
                const month = parts[1].padStart(2, '0');
                const year = parts[2];
                return `${year}-${month}-${day}`;
            }

            function convertFromISO(isoStr) {
                if (!isoStr || isoStr.trim() === '') return '';
                const parts = isoStr.split('-');
                if (parts.length !== 3) return '';
                const year = parts[0];
                const month = parts[1];
                const day = parts[2];
                return `${day}/${month}/${year}`;
            }

            function validateDate(dateStr, isRequired = false) {
                if (!dateStr || dateStr.trim() === '') {
                    if (isRequired) return { valid: false, message: 'Toto pole je povinné.' };
                    return { valid: true, message: '' };
                }
                const parts = dateStr.split('/');
                if (parts.length !== 3) return { valid: false, message: 'Neplatný formát data. Použijte DD/MM/RRRR.' };
                
                const day = parseInt(parts[0], 10);
                const month = parseInt(parts[1], 10);
                const year = parseInt(parts[2], 10);

                if (isNaN(day) || isNaN(month) || isNaN(year)) return { valid: false, message: 'Datum musí obsahovat pouze čísla.' };
                if (day < 1 || day > 31) return { valid: false, message: 'Den musí být mezi 1 a 31.' };
                if (month < 1 || month > 12) return { valid: false, message: 'Měsíc musí být mezi 1 a 12.' };
                if (year < 1900 || year > 2500) return { valid: false, message: 'Rok musí být mezi 1900 a 2500.' };

                const date = new Date(year, month - 1, day);
                if (date.getFullYear() !== year || date.getMonth() !== month - 1 || date.getDate() !== day) {
                    return { valid: false, message: 'Neplatné datum (např. 30. února neexistuje).' };
                }
                return { valid: true, message: '' };
            }

            function showError(input, errorDiv, message) {
                input.classList.add('is-invalid');
                errorDiv.textContent = message;
                errorDiv.style.display = 'block';
            }

            function clearError(input, errorDiv) {
                input.classList.remove('is-invalid');
                errorDiv.textContent = '';
                errorDiv.style.display = 'none';
            }

            function syncStartDate() {
                const isoDate = convertToISO(startDisplay.value);
                startHidden.value = isoDate;
            }

            function syncEndDate() {
                const isoDate = convertToISO(endDisplay.value);
                endHidden.value = isoDate;
            }

            // Manuální synchronizace pro případ, že Flatpickr selže (Fallback)
            if (startHidden.value) {
                startDisplay.value = convertFromISO(startHidden.value);
            }
            if (endHidden.value) {
                endDisplay.value = convertFromISO(endHidden.value);
            }

            startDisplay.addEventListener('blur', function() {
                syncStartDate();
                const validation = validateDate(this.value, true);
                if (!validation.valid) showError(this, startError, validation.message);
                else clearError(this, startError);
            });

            startDisplay.addEventListener('change', syncStartDate);

            endDisplay.addEventListener('blur', function() {
                syncEndDate();
                if (this.value.trim() !== '') {
                    const validation = validateDate(this.value);
                    if (!validation.valid) showError(this, endError, validation.message);
                    else clearError(this, endError);
                } else clearError(this, endError);
            });

            endDisplay.addEventListener('change', syncEndDate);

            nameInput.addEventListener('blur', function() {
                if (this.value.trim() === '') showError(this, nameError, 'Toto pole je povinné.');
                else clearError(this, nameError);
            });

            form.addEventListener('submit', function(e) {
                syncStartDate();
                syncEndDate();

                if (nameInput.value.trim() === '') {
                    e.preventDefault();
                    showError(nameInput, nameError, 'Toto pole je povinné.');
                    nameInput.focus();
                    return;
                } else clearError(nameInput, nameError);

                const startValidation = validateDate(startDisplay.value, true);
                if (!startValidation.valid) {
                    e.preventDefault();
                    showError(startDisplay, startError, startValidation.message);
                    startDisplay.focus();
                    return;
                }

                if (endDisplay.value.trim() !== '') {
                    const endValidation = validateDate(endDisplay.value);
                    if (!endValidation.valid) {
                        e.preventDefault();
                        showError(endDisplay, endError, endValidation.message);
                        endDisplay.focus();
                        return;
                    }
                }
            });

            [startDisplay, endDisplay].forEach(input => {
                input.addEventListener('input', function() {
                    this.value = this.value.replace(/[^\d\/]/g, '');
                    let val = this.value.replace(/\//g, '');
                    if (val.length >= 2) val = val.substring(0, 2) + '/' + val.substring(2);
                    if (val.length >= 5) val = val.substring(0, 5) + '/' + val.substring(5);
                    if (val.length > 10) val = val.substring(0, 10);
                    this.value = val;
                });
            });

            // Initialize Flatpickr
            if (typeof flatpickr !== 'undefined') {
                const baseOptions = {
                    dateFormat: 'd/m/Y',
                    locale: 'cs',
                    allowInput: true,
                    disableMobile: true,
                    minDate: new Date(1900, 0, 1),
                    maxDate: new Date(2500, 11, 31),
                    monthSelectorType: 'dropdown',
                    prevArrow: '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 17 17"><path d="M5.207 8.471l7.146 7.147-0.707 0.707-7.853-7.854 7.854-7.853 0.707 0.707-7.147 7.146z" /></svg>',
                    nextArrow: '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 17 17"><path d="M13.207 8.472l-7.854 7.854-0.707-0.707 7.146-7.146-7.146-7.148 0.707-0.707 7.854 7.854z" /></svg>',
                };

                const startDateISO = startHidden.value;
                const endDateISO = endHidden.value;

                // 1. Inicializace START DATE
                const fpStart = flatpickr(startDisplay, {
                    ...baseOptions,
                    // Default date zkusíme, ale níže ho vynutíme
                    defaultDate: startDateISO || null,
                    onChange: function(selectedDates, dateStr) {
                        syncStartDate();
                        const validation = validateDate(dateStr, true);
                        if (!validation.valid) showError(startDisplay, startError, validation.message);
                        else clearError(startDisplay, startError);
                    }
                });

                // --- KLÍČOVÁ OPRAVA (FORCE UPDATE) ---
                // Pokud máme data ze serveru (hidden input), vnutíme je Flatpickru natvrdo.
                // To přebije jakoukoliv cache prohlížeče.
                if (startDateISO) {
                    fpStart.setDate(startDateISO, true); 
                }
                // -------------------------------------


                // 2. Inicializace END DATE
                const fpEnd = flatpickr(endDisplay, {
                    ...baseOptions,
                    defaultDate: endDateISO || null,
                    onChange: function(selectedDates, dateStr) {
                        syncEndDate();
                        if (dateStr.trim() !== '') {
                            const validation = validateDate(dateStr);
                            if (!validation.valid) showError(endDisplay, endError, validation.message);
                            else clearError(endDisplay, endError);
                        } else clearError(endDisplay, endError);
                    }
                });

                 // --- KLÍČOVÁ OPRAVA (FORCE UPDATE) ---
                if (endDateISO) {
                    fpEnd.setDate(endDateISO, true);
                }
                // -------------------------------------
            }
        };
    })();
</script>