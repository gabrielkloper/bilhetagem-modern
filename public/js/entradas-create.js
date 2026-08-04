/**
 * Entradas Create Page JavaScript
 * Manages the entry form for the ticketing system
 */
document.addEventListener('DOMContentLoaded', function() {
    let currentEventData = null;

    // Get the store URL from the form's data attribute
    const entryForm = document.getElementById('entry-form');
    const storeUrl = entryForm ? entryForm.dataset.storeUrl : '/admin/entradas';

    // Brazilian Real currency formatting
    function formatMoeda(input) {
        let valor = input.value.replace(/\D/g, '');
        if (valor === '') {
            input.value = '';
            return;
        }

        valor = (parseInt(valor) / 100).toFixed(2);
        valor = valor.replace('.', ',');
        valor = valor.replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
        input.value = valor;
    }

    // Make formatMoeda available globally
    window.formatMoeda = formatMoeda;

    // Event selection handler
    const eventoSelect = document.getElementById('evento_select');
    const entryFormContainer = document.getElementById('entry-form-container');
    const eventStats = document.getElementById('event-stats');
    const loadingOverlay = document.getElementById('loading-overlay');

    eventoSelect.addEventListener('change', function() {
        const eventoId = this.value;
        if (eventoId) {
            loadEventData(eventoId);
        } else {
            entryFormContainer.classList.add('hidden');
            eventStats.classList.add('hidden');
        }
    });

    // Load event data
    function loadEventData(eventoId) {
        loadingOverlay.classList.remove('hidden');

        fetch(`/admin/entradas/evento/${eventoId}/data`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na requisição: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                currentEventData = data;
                populateEventData(data);
                entryFormContainer.classList.remove('hidden');
                eventStats.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Erro ao carregar dados do evento:', error);
                alert('Erro ao carregar dados do evento: ' + error.message);
            })
            .finally(() => {
                loadingOverlay.classList.add('hidden');
            });
    }

    // Populate form with event data
    function populateEventData(data) {
        // Store event data for payment processing
        window.currentEventData = data;

        try {
            // Update stats - check if elements exist first
            const presentes = data.estatisticas.presentes;
            const capacidade = data.evento.capacidade;
            const disponivel = capacidade - presentes;

            const presentesEl = document.getElementById('presentes-count');
            const capacidadeEl = document.getElementById('capacidade-info');
            const disponivelEl = document.getElementById('disponivel-count');

            if (presentesEl) presentesEl.textContent = presentes;
            if (capacidadeEl) capacidadeEl.textContent = `${presentes}/${capacidade}`;
            if (disponivelEl) disponivelEl.textContent = disponivel;

            // Update colors based on capacity and show alerts
            if (disponivelEl) {
                updateCapacityAlerts(disponivel, capacidade);

                if (disponivel <= 0) {
                    disponivelEl.className = 'font-semibold text-red-600';
                } else if (disponivel <= 10) {
                    disponivelEl.className = 'font-semibold text-orange-600';
                } else {
                    disponivelEl.className = 'font-semibold text-blue-600';
                }
            }

            // Populate payment methods based on event settings
            populatePaymentMethods(data.evento);

            // Populate responsáveis
            const responsavelSelect = document.getElementById('responsavel_id');
            if (responsavelSelect) {
                responsavelSelect.innerHTML = '<option value="">Selecione um responsável</option>';
                if (data.responsaveis && Array.isArray(data.responsaveis)) {
                    data.responsaveis.forEach(function(responsavel) {
                        try {
                            const option = document.createElement('option');
                            option.value = responsavel.id;
                            option.textContent = responsavel.nome;
                            responsavelSelect.appendChild(option);
                        } catch (error) {
                            console.error('Error adding responsavel option:', error);
                        }
                    });
                }
            }

            // Populate pacotes
            const pacoteSelect = document.getElementById('pacote_id');
            if (pacoteSelect) {
                pacoteSelect.innerHTML = '<option value="">Selecione um pacote</option>';
                if (data.evento.pacotes && Array.isArray(data.evento.pacotes)) {
                    data.evento.pacotes.forEach(function(pacote) {
                        try {
                            const option = document.createElement('option');
                            option.value = pacote.id;
                            option.textContent = `${pacote.descricao} - R$ ${parseFloat(pacote.valor).toLocaleString('pt-BR', {minimumFractionDigits: 2})}`;
                            pacoteSelect.appendChild(option);
                        } catch (error) {
                            console.error('Error adding pacote option:', error);
                        }
                    });
                }
            }

            // Populate pré-vendas
            const prevendaSelect = document.getElementById('prevenda_id');
            if (prevendaSelect) {
                prevendaSelect.innerHTML = '<option value="">Nenhuma pré-venda</option>';
                if (data.prevendas && Array.isArray(data.prevendas)) {
                    data.prevendas.forEach(function(prevenda) {
                        try {
                            const option = document.createElement('option');
                            option.value = prevenda.id;
                            option.textContent = `ID: ${prevenda.id} - R$ ${parseFloat(prevenda.valor).toLocaleString('pt-BR', {minimumFractionDigits: 2})}`;
                            prevendaSelect.appendChild(option);
                        } catch (error) {
                            console.error('Error adding prevenda option:', error);
                        }
                    });
                }
            }

            // Populate perfis de acesso
            const perfilSelect = document.getElementById('perfil_acesso_id');
            if (perfilSelect) {
                perfilSelect.innerHTML = '<option value="">Perfil sugerido automático</option>';
                if (data.perfis_acesso && Array.isArray(data.perfis_acesso)) {
                    data.perfis_acesso.forEach(function(perfil) {
                        try {
                            const option = document.createElement('option');
                            option.value = perfil.id;
                            option.textContent = perfil.titulo;
                            perfilSelect.appendChild(option);
                        } catch (error) {
                            console.error('Error adding perfil option:', error);
                        }
                    });
                }
            }
        } catch (error) {
            console.error('Error populating event data:', error);
            alert('Erro ao carregar dados do evento. Tente novamente.');
        }
    }

    // Responsável selection handler
    const responsavelSelect = document.getElementById('responsavel_id');
    const vinculadoContainer = document.getElementById('vinculado-container');
    const vinculadoSelect = document.getElementById('vinculado_id');

    responsavelSelect.addEventListener('change', function() {
        const responsavelId = this.value;
        const eventoId = eventoSelect.value;

        if (!responsavelId) {
            vinculadoContainer.classList.add('hidden');
            hideSubsequentContainers();
            return;
        }

        // Load vinculados for selected responsável
        fetch(`/admin/entradas/vinculados?responsavel_id=${responsavelId}&evento_id=${eventoId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (vinculadoSelect) {
                    vinculadoSelect.innerHTML = '<option value="">Selecione uma criança</option>';
                    if (data.vinculados && Array.isArray(data.vinculados)) {
                        data.vinculados.forEach(function(vinculado) {
                            try {
                                const option = document.createElement('option');
                                option.value = vinculado.id;
                                option.textContent = `${vinculado.nome} (${vinculado.idade} anos)`;
                                option.setAttribute('data-idade', vinculado.idade);
                                option.setAttribute('data-vinculo', vinculado.vinculo);
                                option.setAttribute('data-perfil-sugerido-id', vinculado.perfil_sugerido_id || '');
                                option.setAttribute('data-perfil-sugerido', vinculado.perfil_sugerido || '');
                                vinculadoSelect.appendChild(option);
                            } catch (error) {
                                console.error('Error creating vinculado option:', error);
                            }
                        });
                    }

                    if (vinculadoContainer) {
                        vinculadoContainer.classList.remove('hidden');
                    }
                    hideSubsequentContainers();
                } else {
                    console.error('vinculadoSelect element not found');
                }
            })
            .catch(error => {
                console.error('Erro ao carregar vinculados:', error);
                if (window.SweetAlert) {
                    SweetAlert.error('Erro!', 'Não foi possível carregar as crianças do responsável.');
                } else {
                    alert('Erro ao carregar crianças do responsável: ' + error.message);
                }
            });
    });

    // Vinculado selection handler
    if (vinculadoSelect) {
        vinculadoSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const vinculadoId = this.value;

            if (!vinculadoId) {
                hideVinculadoInfo();
                hideSubsequentContainers();
                return;
            }

            // Show vinculado info
            const idade = selectedOption.getAttribute('data-idade');
            const vinculo = selectedOption.getAttribute('data-vinculo');
            const perfilSugeridoId = selectedOption.getAttribute('data-perfil-sugerido-id');
            const perfilSugerido = selectedOption.getAttribute('data-perfil-sugerido');

            const vinculadoIdadeEl = document.getElementById('vinculado-idade');
            const vinculadoVinculoEl = document.getElementById('vinculado-vinculo');
            const vinculadoInfoEl = document.getElementById('vinculado-info');

            if (vinculadoIdadeEl) vinculadoIdadeEl.textContent = idade;
            if (vinculadoVinculoEl) vinculadoVinculoEl.textContent = vinculo;
            if (vinculadoInfoEl) vinculadoInfoEl.classList.remove('hidden');

            // Check for duplicate entry
            checkDuplicateEntry(vinculadoId, selectedOption.textContent.split(' (')[0]);

            // Show perfil container and set suggested perfil
            const perfilContainer = document.getElementById('perfil-container');
            const perfilInfo = document.getElementById('perfil-info');
            const perfilSugeridoSpan = document.getElementById('perfil-sugerido');

            if (perfilContainer) perfilContainer.classList.remove('hidden');

            if (perfilSugerido && perfilInfo && perfilSugeridoSpan) {
                perfilInfo.classList.remove('hidden');
                perfilSugeridoSpan.textContent = perfilSugerido;

                // Pre-select suggested perfil
                if (perfilSugeridoId) {
                    const perfilAcessoEl = document.getElementById('perfil_acesso_id');
                    if (perfilAcessoEl) perfilAcessoEl.value = perfilSugeridoId;
                }
            } else if (perfilInfo) {
                perfilInfo.classList.add('hidden');
            }

            // Show pacote container
            const pacoteContainer = document.getElementById('pacote-container');
            const prevendaContainer = document.getElementById('prevenda-container');
            if (pacoteContainer) pacoteContainer.classList.remove('hidden');
            if (prevendaContainer) prevendaContainer.classList.remove('hidden');
        });
    }

    // Pacote selection handler
    const pacoteSelect = document.getElementById('pacote_id');
    if (pacoteSelect) {
        pacoteSelect.addEventListener('change', function() {
            const pacoteId = this.value;

            if (pacoteId && currentEventData && currentEventData.evento.pacotes) {
                const pacote = currentEventData.evento.pacotes.find(p => p.id == pacoteId);
                if (pacote) {
                    const pacoteDuracaoEl = document.getElementById('pacote-duracao');
                    const pacoteValorEl = document.getElementById('pacote-valor');
                    const pacoteInfoEl = document.getElementById('pacote-info');

                    if (pacoteDuracaoEl) pacoteDuracaoEl.textContent = pacote.duracao;
                    if (pacoteValorEl) pacoteValorEl.textContent = `R$ ${parseFloat(pacote.valor).toLocaleString('pt-BR', {minimumFractionDigits: 2})}`;
                    if (pacoteInfoEl) pacoteInfoEl.classList.remove('hidden');

                    // Show payment section and update price
                    showPaymentSection(pacote);
                }
            } else {
                const pacoteInfoEl = document.getElementById('pacote-info');
                if (pacoteInfoEl) pacoteInfoEl.classList.add('hidden');
                hidePaymentSection();
            }
        });
    }

    function hideVinculadoInfo() {
        const vinculadoInfoEl = document.getElementById('vinculado-info');
        const duplicateWarningEl = document.getElementById('duplicate-warning');
        if (vinculadoInfoEl) vinculadoInfoEl.classList.add('hidden');
        if (duplicateWarningEl) duplicateWarningEl.classList.add('hidden');
    }

    function hideSubsequentContainers() {
        const containers = [
            'perfil-container',
            'pacote-container',
            'prevenda-container',
            'perfil-info',
            'pacote-info',
            'duplicate-warning'
        ];

        containers.forEach(function(containerId) {
            const element = document.getElementById(containerId);
            if (element) element.classList.add('hidden');
        });

        hidePaymentSection();
    }

    // Check for duplicate entry
    function checkDuplicateEntry(vinculadoId, vinculadoNome) {
        const eventoId = eventoSelect ? eventoSelect.value : null;

        if (!eventoId) {
            console.error('Evento ID not found');
            return;
        }

        fetch(`/admin/entradas/check-duplicate?evento_id=${eventoId}&vinculado_id=${vinculadoId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                const duplicateWarning = document.getElementById('duplicate-warning');
                const duplicateChildName = document.getElementById('duplicate-child-name');
                const duplicateEntryTime = document.getElementById('duplicate-entry-time');
                const duplicatePackageName = document.getElementById('duplicate-package-name');

                if (data.exists && duplicateWarning) {
                    // Show duplicate warning
                    if (duplicateChildName) duplicateChildName.textContent = vinculadoNome;
                    if (duplicateEntryTime) duplicateEntryTime.textContent = data.entry_time;
                    if (duplicatePackageName) duplicatePackageName.textContent = data.package_name;
                    duplicateWarning.classList.remove('hidden');
                } else if (duplicateWarning) {
                    duplicateWarning.classList.add('hidden');
                }
            })
            .catch(error => {
                console.error('Erro ao verificar entrada duplicada:', error);
                const duplicateWarning = document.getElementById('duplicate-warning');
                if (duplicateWarning) duplicateWarning.classList.add('hidden');
            });
    }

    // Update capacity alerts based on available spots
    function updateCapacityAlerts(disponivel, capacidade) {
        const alertContainer = document.getElementById('capacity-alert-container');
        const alertElement = document.getElementById('capacity-alert');
        const alertIcon = document.getElementById('capacity-alert-icon');
        const alertText = document.getElementById('capacity-alert-text');
        const alertDetail = document.getElementById('capacity-alert-detail');

        // Check if all required elements exist
        if (!alertContainer || !alertElement || !alertIcon || !alertText || !alertDetail) {
            console.warn('Some capacity alert elements not found');
            return;
        }

        const percentageUsed = ((capacidade - disponivel) / capacidade) * 100;

        if (disponivel <= 0) {
            // Full capacity - critical alert
            alertContainer.classList.remove('hidden');
            alertElement.className = 'p-3 rounded-md border bg-red-50 border-red-200';
            alertIcon.className = 'fas fa-exclamation-triangle text-red-400';
            alertText.className = 'text-sm font-medium text-red-800';
            alertText.textContent = 'Capacidade Esgotada!';
            alertDetail.className = 'text-xs mt-1 text-red-600';
            alertDetail.textContent = 'O evento atingiu a capacidade máxima. Novas entradas podem ser bloqueadas.';
        } else if (disponivel <= 5) {
            // Very low capacity - warning alert
            alertContainer.classList.remove('hidden');
            alertElement.className = 'p-3 rounded-md border bg-orange-50 border-orange-200';
            alertIcon.className = 'fas fa-exclamation-circle text-orange-400';
            alertText.className = 'text-sm font-medium text-orange-800';
            alertText.textContent = `Capacidade Crítica! Apenas ${disponivel} vagas restantes`;
            alertDetail.className = 'text-xs mt-1 text-orange-600';
            alertDetail.textContent = `${percentageUsed.toFixed(1)}% da capacidade ocupada. Monitore as entradas de perto.`;
        } else if (disponivel <= 15) {
            // Moderate capacity - info alert
            alertContainer.classList.remove('hidden');
            alertElement.className = 'p-3 rounded-md border bg-yellow-50 border-yellow-200';
            alertIcon.className = 'fas fa-info-circle text-yellow-400';
            alertText.className = 'text-sm font-medium text-yellow-800';
            alertText.textContent = `Atenção: ${disponivel} vagas disponíveis`;
            alertDetail.className = 'text-xs mt-1 text-yellow-600';
            alertDetail.textContent = `${percentageUsed.toFixed(1)}% da capacidade ocupada. Prepare-se para possível lotação.`;
        } else if (percentageUsed >= 50) {
            // Half capacity reached - light info
            alertContainer.classList.remove('hidden');
            alertElement.className = 'p-3 rounded-md border bg-blue-50 border-blue-200';
            alertIcon.className = 'fas fa-info-circle text-blue-400';
            alertText.className = 'text-sm font-medium text-blue-800';
            alertText.textContent = `Evento com boa ocupação: ${disponivel} vagas`;
            alertDetail.className = 'text-xs mt-1 text-blue-600';
            alertDetail.textContent = `${percentageUsed.toFixed(1)}% da capacidade ocupada. Evento está se aproximando da metade.`;
        } else {
            // Good capacity available - hide alert
            alertContainer.classList.add('hidden');
        }
    }

    // Payment Management Functions
    function populatePaymentMethods(evento) {
        const paymentSelect = document.getElementById('payment_method');
        if (!paymentSelect) {
            console.error('Payment method select element not found');
            return;
        }

        paymentSelect.innerHTML = '<option value="">Selecione a forma</option>';

        if (evento.aceita_dinheiro) {
            paymentSelect.appendChild(new Option('Dinheiro', 'dinheiro'));
        }
        if (evento.aceita_cartao) {
            paymentSelect.appendChild(new Option('Cartão de Débito', 'cartao_debito'));
            paymentSelect.appendChild(new Option('Cartão de Crédito', 'cartao_credito'));
        }
        if (evento.aceita_pix) {
            paymentSelect.appendChild(new Option('PIX', 'pix'));
        }
        if (evento.aceita_gratuito) {
            paymentSelect.appendChild(new Option('Gratuito', 'gratuito'));
        }
    }

    function showPaymentSection(pacote) {
        const paymentSection = document.getElementById('payment-section');
        if (!paymentSection) {
            console.error('Payment section element not found');
            return;
        }

        paymentSection.classList.remove('hidden');

        // Update package price display
        const packagePrice = parseFloat(pacote.valor);
        const packagePriceEl = document.getElementById('package-price');
        if (packagePriceEl) {
            packagePriceEl.textContent = `R$ ${packagePrice.toLocaleString('pt-BR', {minimumFractionDigits: 2})}`;
        }

        // Store current package data
        window.currentPackage = pacote;

        // Show payment status
        const paymentStatusContainer = document.getElementById('payment-status-container');
        if (paymentStatusContainer) {
            paymentStatusContainer.classList.remove('hidden');
            updatePaymentStatus('pending', 'Aguardando forma de pagamento');
        }
    }

    function hidePaymentSection() {
        const paymentElements = [
            'payment-section',
            'amount-paid-container',
            'change-container',
            'payment-status-container'
        ];

        paymentElements.forEach(function(elementId) {
            const element = document.getElementById(elementId);
            if (element) element.classList.add('hidden');
        });
    }

    function updatePaymentStatus(status, message) {
        const statusElement = document.getElementById('payment-status');
        if (!statusElement) {
            console.error('Payment status element not found');
            return;
        }

        const statusClasses = {
            'pending': 'bg-yellow-100 text-yellow-800',
            'valid': 'bg-green-100 text-green-800',
            'invalid': 'bg-red-100 text-red-800',
            'processing': 'bg-blue-100 text-blue-800'
        };

        const statusIcons = {
            'pending': 'fas fa-clock',
            'valid': 'fas fa-check-circle',
            'invalid': 'fas fa-exclamation-triangle',
            'processing': 'fas fa-spinner fa-spin'
        };

        statusElement.className = `inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusClasses[status] || statusClasses.pending}`;
        statusElement.innerHTML = `<i class="${statusIcons[status] || statusIcons.pending} mr-1"></i>${message}`;
    }

    // Payment method selection handler
    const paymentMethodSelect = document.getElementById('payment_method');
    if (paymentMethodSelect) {
        paymentMethodSelect.addEventListener('change', function() {
            const paymentMethod = this.value;
            const amountPaidContainer = document.getElementById('amount-paid-container');
            const changeContainer = document.getElementById('change-container');

            if (paymentMethod === 'dinheiro') {
                if (amountPaidContainer) amountPaidContainer.classList.remove('hidden');
                if (changeContainer) changeContainer.classList.remove('hidden');
                updatePaymentStatus('pending', 'Digite o valor recebido');
            } else if (paymentMethod === 'gratuito') {
                if (amountPaidContainer) amountPaidContainer.classList.add('hidden');
                if (changeContainer) changeContainer.classList.add('hidden');
                updatePaymentStatus('valid', 'Entrada gratuita');
            } else if (paymentMethod) {
                if (amountPaidContainer) amountPaidContainer.classList.add('hidden');
                if (changeContainer) changeContainer.classList.add('hidden');
                updatePaymentStatus('valid', 'Pagamento confirmado');
            } else {
                if (amountPaidContainer) amountPaidContainer.classList.add('hidden');
                if (changeContainer) changeContainer.classList.add('hidden');
                updatePaymentStatus('pending', 'Selecione forma de pagamento');
            }
        });
    }

    // Amount paid input handler with real-time change calculation
    const amountPaidInput = document.getElementById('amount_paid');
    if (amountPaidInput) {
        amountPaidInput.addEventListener('input', function() {
            const input = this;

            // Format as currency
            let value = input.value.replace(/\D/g, '');
            if (value === '') {
                input.value = '';
                updateChangeDisplay(0);
                return;
            }

            value = (parseInt(value) / 100).toFixed(2);
            input.value = `R$ ${value.replace('.', ',')}`;

            // Calculate and display change
            const amountPaid = parseFloat(value);
            updateChangeDisplay(amountPaid);
        });
    }

    function updateChangeDisplay(amountPaid) {
        if (!window.currentPackage) return;

        const packagePrice = parseFloat(window.currentPackage.valor);
        const change = amountPaid - packagePrice;
        const changeElement = document.getElementById('change-amount');
        const changeDisplay = document.getElementById('change-display');

        if (amountPaid === 0) {
            changeElement.textContent = 'R$ 0,00';
            changeDisplay.className = 'mt-1 block w-full px-3 py-2 rounded-md text-sm bg-gray-50 border border-gray-300';
            updatePaymentStatus('pending', 'Digite o valor recebido');
        } else if (change < 0) {
            changeElement.textContent = `Falta: R$ ${Math.abs(change).toLocaleString('pt-BR', {minimumFractionDigits: 2})}`;
            changeDisplay.className = 'mt-1 block w-full px-3 py-2 rounded-md text-sm bg-red-50 border border-red-300';
            updatePaymentStatus('invalid', 'Valor insuficiente');
        } else {
            changeElement.textContent = `R$ ${change.toLocaleString('pt-BR', {minimumFractionDigits: 2})}`;
            changeDisplay.className = 'mt-1 block w-full px-3 py-2 rounded-md text-sm bg-green-50 border border-green-300';
            updatePaymentStatus('valid', change > 0 ? 'Troco calculado' : 'Valor exato');
        }
    }

    // Form submission
    if (entryForm) {
        entryForm.addEventListener('submit', function(e) {
            e.preventDefault();

            if (!currentEventData) {
                alert('Selecione um evento primeiro');
                return;
            }

            // Validate required fields
            const vinculadoId = document.getElementById('vinculado_id').value;
            const pacoteId = document.getElementById('pacote_id').value;
            const paymentMethod = document.getElementById('payment_method').value;

            if (!vinculadoId) {
                if (window.SweetAlert) {
                    SweetAlert.warning('Atenção!', 'Selecione uma criança para continuar.');
                } else {
                    alert('Selecione uma criança para continuar.');
                }
                return;
            }

            if (!pacoteId) {
                if (window.SweetAlert) {
                    SweetAlert.warning('Atenção!', 'Selecione um pacote para continuar.');
                } else {
                    alert('Selecione um pacote para continuar.');
                }
                return;
            }

            if (!paymentMethod) {
                if (window.SweetAlert) {
                    SweetAlert.warning('Atenção!', 'Selecione a forma de pagamento.');
                } else {
                    alert('Selecione a forma de pagamento.');
                }
                return;
            }

            // Validate payment for cash payments
            if (paymentMethod === 'dinheiro') {
                const amountPaidInputVal = document.getElementById('amount_paid').value;
                if (!amountPaidInputVal) {
                    if (window.SweetAlert) {
                        SweetAlert.warning('Atenção!', 'Digite o valor recebido em dinheiro.');
                    } else {
                        alert('Digite o valor recebido em dinheiro.');
                    }
                    return;
                }

                const amountPaid = parseFloat(amountPaidInputVal.replace('R$ ', '').replace(',', '.'));
                const packagePrice = parseFloat(window.currentPackage.valor);

                if (amountPaid < packagePrice) {
                    if (window.SweetAlert) {
                        SweetAlert.warning('Atenção!', 'Valor recebido é insuficiente para o pacote selecionado.');
                    } else {
                        alert('Valor recebido é insuficiente para o pacote selecionado.');
                    }
                    return;
                }
            }

            // Check capacity
            if (currentEventData.estatisticas.capacidade_disponivel <= 0) {
                if (!confirm('Capacidade máxima atingida. Deseja continuar?')) {
                    return;
                }
            }

            const formData = new FormData(this);
            const eventoId = eventoSelect.value;

            // Add additional data
            formData.append('evento_id', eventoId);

            loadingOverlay.classList.remove('hidden');

            fetch(storeUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Store last successful entry data for quick actions and receipt
                    window.lastEntryData = {
                        entrada_id: data.entrada.id,
                        responsavel_id: document.getElementById('responsavel_id').value,
                        responsavel_nome: document.getElementById('responsavel_id').options[document.getElementById('responsavel_id').selectedIndex].text,
                        vinculado_nome: document.getElementById('vinculado_id').options[document.getElementById('vinculado_id').selectedIndex].text,
                        pacote_nome: document.getElementById('pacote_id').options[document.getElementById('pacote_id').selectedIndex].text,
                        evento_nome: currentEventData.evento.titulo,
                        data_entrada: new Date().toLocaleDateString('pt-BR') + ' ' + new Date().toLocaleTimeString('pt-BR'),
                        payment_method: document.getElementById('payment_method').value,
                        package_price: window.currentPackage ? window.currentPackage.valor : 0,
                        amount_paid: document.getElementById('amount_paid').value || window.currentPackage?.valor || 0,
                        change: 0
                    };

                    // Calculate change for receipt
                    if (window.lastEntryData.payment_method === 'dinheiro' && document.getElementById('amount_paid').value) {
                        const amountPaid = parseFloat(document.getElementById('amount_paid').value.replace('R$ ', '').replace(',', '.'));
                        const packagePrice = parseFloat(window.currentPackage.valor);
                        window.lastEntryData.change = amountPaid - packagePrice;
                        window.lastEntryData.amount_paid = amountPaid;
                    } else if (window.lastEntryData.payment_method === 'gratuito') {
                        window.lastEntryData.amount_paid = 0;
                    }

                    // Show enhanced success message
                    document.getElementById('success-text').textContent = data.success;
                    document.getElementById('success-details').textContent = `${window.lastEntryData.vinculado_nome} - ${window.lastEntryData.pacote_nome}`;
                    document.getElementById('success-message').classList.remove('hidden');

                    // Clear form
                    clearForm();

                    // Reload event data to update stats
                    loadEventData(eventoId);

                    // Auto-hide success message after 8 seconds (longer for quick actions)
                    setTimeout(function() {
                        document.getElementById('success-message').classList.add('hidden');
                    }, 8000);
                } else {
                    throw new Error(data.error || 'Erro desconhecido');
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao registrar entrada: ' + error.message);
            })
            .finally(() => {
                loadingOverlay.classList.add('hidden');
            });
        });
    }

    // Clear form
    const clearFormBtn = document.getElementById('clear-form');
    if (clearFormBtn) {
        clearFormBtn.addEventListener('click', function() {
            clearForm();
        });
    }

    // Quick action buttons
    const generateReceiptBtn = document.getElementById('generate-receipt');
    if (generateReceiptBtn) {
        generateReceiptBtn.addEventListener('click', function() {
            if (window.lastEntryData) {
                generateReceipt();
            }
        });
    }

    const addAnotherEntryBtn = document.getElementById('add-another-entry');
    if (addAnotherEntryBtn) {
        addAnotherEntryBtn.addEventListener('click', function() {
            // Hide success message and focus on event selector for quick new entry
            document.getElementById('success-message').classList.add('hidden');
            document.getElementById('responsavel_id').focus();
        });
    }

    const sameFamilyEntryBtn = document.getElementById('same-family-entry');
    if (sameFamilyEntryBtn) {
        sameFamilyEntryBtn.addEventListener('click', function() {
            // Hide success message and pre-select the same responsável
            document.getElementById('success-message').classList.add('hidden');

            if (window.lastEntryData && window.lastEntryData.responsavel_id) {
                const responsavelSelect = document.getElementById('responsavel_id');
                responsavelSelect.value = window.lastEntryData.responsavel_id;

                // Trigger the change event to load vinculados
                responsavelSelect.dispatchEvent(new Event('change'));

                // Focus on vinculado selector
                setTimeout(() => {
                    document.getElementById('vinculado_id').focus();
                }, 500);
            }
        });
    }

    // Receipt modal handlers
    const closeReceiptBtn = document.getElementById('close-receipt');
    const closeReceiptBtnAlt = document.getElementById('close-receipt-btn');

    if (closeReceiptBtn) {
        closeReceiptBtn.addEventListener('click', closeReceiptModal);
    }
    if (closeReceiptBtnAlt) {
        closeReceiptBtnAlt.addEventListener('click', closeReceiptModal);
    }

    const printReceiptBtn = document.getElementById('print-receipt');
    if (printReceiptBtn) {
        printReceiptBtn.addEventListener('click', function() {
            const receiptContent = document.getElementById('receipt-content').innerHTML;

            // Try to open print window
            const printWindow = window.open('', '_blank', 'width=400,height=600');

            // Check if popup was blocked
            if (!printWindow || printWindow.closed) {
                alert('Por favor, permita pop-ups para imprimir o comprovante ou use Ctrl+P para imprimir esta página.');
                return;
            }

            try {
                printWindow.document.write(
                    '<html>' +
                        '<head>' +
                            '<title>Comprovante de Entrada</title>' +
                            '<meta charset="utf-8">' +
                            '<style>' +
                                'body { font-family: "Courier New", monospace; margin: 20px; font-size: 12px; }' +
                                '.receipt { max-width: 300px; margin: 0 auto; }' +
                                '@media print { body { margin: 0; } .receipt { max-width: none; } }' +
                            '</style>' +
                        '</head>' +
                        '<body onload="window.print(); setTimeout(function(){ window.close(); }, 100);">' +
                            '<div class="receipt">' + receiptContent + '</div>' +
                        '</body>' +
                    '</html>'
                );
                printWindow.document.close();
            } catch (error) {
                console.error('Erro ao abrir janela de impressão:', error);
                alert('Erro ao imprimir. Tente usar Ctrl+P para imprimir esta página.');
                printWindow.close();
            }
        });
    }

    function closeReceiptModal() {
        document.getElementById('receipt-modal').classList.add('hidden');
    }

    function generateReceipt() {
        const data = window.lastEntryData;
        const paymentMethodLabels = {
            'dinheiro': 'Dinheiro',
            'cartao_debito': 'Cartão Débito',
            'cartao_credito': 'Cartão Crédito',
            'pix': 'PIX',
            'gratuito': 'Gratuito'
        };

        let receiptContent = '<div class="text-center border-b-2 border-dashed border-gray-400 pb-4 mb-4">' +
                '<h4 class="font-bold text-lg">COMPROVANTE DE ENTRADA</h4>' +
                '<p class="text-sm">' + data.evento_nome + '</p>' +
            '</div>' +

            '<div class="space-y-2 border-b-2 border-dashed border-gray-400 pb-4 mb-4">' +
                '<div class="flex justify-between">' +
                    '<span>Data/Hora:</span>' +
                    '<span>' + data.data_entrada + '</span>' +
                '</div>' +
                '<div class="flex justify-between">' +
                    '<span>Entrada Nº:</span>' +
                    '<span>' + data.entrada_id + '</span>' +
                '</div>' +
            '</div>' +

            '<div class="space-y-2 border-b-2 border-dashed border-gray-400 pb-4 mb-4">' +
                '<div class="flex justify-between">' +
                    '<span>Criança:</span>' +
                    '<span>' + data.vinculado_nome + '</span>' +
                '</div>' +
                '<div class="flex justify-between">' +
                    '<span>Responsável:</span>' +
                    '<span>' + data.responsavel_nome + '</span>' +
                '</div>' +
            '</div>' +

            '<div class="space-y-2 border-b-2 border-dashed border-gray-400 pb-4 mb-4">' +
                '<div class="flex justify-between">' +
                    '<span>Pacote:</span>' +
                    '<span>' + data.pacote_nome + '</span>' +
                '</div>' +
                '<div class="flex justify-between">' +
                    '<span>Valor:</span>' +
                    '<span>R$ ' + parseFloat(data.package_price).toLocaleString('pt-BR', {minimumFractionDigits: 2}) + '</span>' +
                '</div>' +
            '</div>' +

            '<div class="space-y-2 border-b-2 border-dashed border-gray-400 pb-4 mb-4">' +
                '<div class="flex justify-between">' +
                    '<span>Forma Pgto:</span>' +
                    '<span>' + (paymentMethodLabels[data.payment_method] || data.payment_method) + '</span>' +
                '</div>';

        if (data.payment_method === 'dinheiro') {
            receiptContent += '<div class="flex justify-between">' +
                    '<span>Valor Recebido:</span>' +
                    '<span>R$ ' + parseFloat(data.amount_paid).toLocaleString('pt-BR', {minimumFractionDigits: 2}) + '</span>' +
                '</div>' +
                '<div class="flex justify-between font-bold">' +
                    '<span>Troco:</span>' +
                    '<span>R$ ' + parseFloat(data.change).toLocaleString('pt-BR', {minimumFractionDigits: 2}) + '</span>' +
                '</div>';
        }

        receiptContent += '</div>' +

            '<div class="text-center text-xs">' +
                '<p>Sistema de Bilhetagem</p>' +
                '<p>Entrada válida apenas para este evento</p>' +
                '<p>Guarde este comprovante</p>' +
            '</div>';

        document.getElementById('receipt-content').innerHTML = receiptContent;
        document.getElementById('receipt-modal').classList.remove('hidden');
    }

    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Alt + N = New Entry (focus responsável)
        if (e.altKey && e.key === 'n') {
            e.preventDefault();
            document.getElementById('responsavel_id').focus();
        }

        // Alt + S = Same Family (trigger same family button if visible)
        if (e.altKey && e.key === 's') {
            e.preventDefault();
            const sameFamilyBtn = document.getElementById('same-family-entry');
            if (sameFamilyBtn && !sameFamilyBtn.closest('#success-message').classList.contains('hidden')) {
                sameFamilyBtn.click();
            }
        }
    });

    function clearForm() {
        if (entryForm) {
            entryForm.reset();
        }
        hideSubsequentContainers();
        hideVinculadoInfo();

        // Reset form to initial state
        if (vinculadoContainer) {
            vinculadoContainer.classList.add('hidden');
        }
        const successMessage = document.getElementById('success-message');
        if (successMessage) {
            successMessage.classList.add('hidden');
        }
    }
});
