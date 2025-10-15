/**
 * SweetAlert2 Configuration for Bilhetagem System
 * Provides consistent alert styling and functionality across the application
 * 
 * USAGE EXAMPLES:
 * 
 * // Success message
 * SweetAlert.success('Sucesso!', 'Operação realizada com sucesso.');
 * 
 * // Error message
 * SweetAlert.error('Erro!', 'Algo deu errado.');
 * 
 * // Confirmation dialog
 * SweetAlert.confirm('Tem certeza?', 'Esta ação não pode ser desfeita.')
 *   .then((result) => {
 *     if (result.isConfirmed) {
 *       // User confirmed
 *     }
 *   });
 * 
 * // Delete confirmation (specialized)
 * SweetAlert.confirmDelete('este usuário').then((result) => {
 *   if (result.isConfirmed) {
 *     // Proceed with deletion
 *   }
 * });
 * 
 * // Loading dialog
 * SweetAlert.loading('Processando...', 'Aguarde...');
 * // Close loading: SweetAlert.close();
 * 
 * // Toast notification
 * SweetAlert.toast('success', 'Salvo com sucesso!');
 * 
 * // Warning
 * SweetAlert.warning('Atenção!', 'Verifique os dados informados.');
 * 
 * // Info
 * SweetAlert.info('Informação', 'Dados atualizados.');
 */
class SweetAlertConfig {
    constructor() {
        this.defaultConfig = {
            customClass: {
                popup: 'rounded-lg shadow-xl',
                header: 'border-b border-gray-200 pb-4',
                title: 'text-lg font-semibold text-gray-900',
                content: 'text-sm text-gray-600',
                confirmButton: 'bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500',
                cancelButton: 'bg-white hover:bg-gray-50 text-gray-700 font-medium py-2 px-4 border border-gray-300 rounded-md shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-3',
                denyButton: 'bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 mr-3'
            },
            buttonsStyling: false,
            reverseButtons: true,
            focusConfirm: false,
            allowOutsideClick: false,
            allowEscapeKey: true,
            showClass: {
                popup: 'animate__animated animate__fadeInDown animate__faster'
            },
            hideClass: {
                popup: 'animate__animated animate__fadeOutUp animate__faster'
            }
        };
        
        // Configure SweetAlert2 globally
        this.configure();
    }

    configure() {
        // Add Animate.css for animations
        if (!document.querySelector('link[href*="animate.css"]')) {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = 'https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css';
            document.head.appendChild(link);
        }
    }

    /**
     * Success alert
     * @param {string} title - Alert title
     * @param {string} text - Alert text (optional)
     * @param {object} options - Additional options
     */
    success(title, text = null, options = {}) {
        return Swal.fire({
            ...this.defaultConfig,
            icon: 'success',
            title: title,
            text: text,
            confirmButtonText: 'OK',
            timer: options.timer || 3000,
            timerProgressBar: true,
            ...options
        });
    }

    /**
     * Error alert
     * @param {string} title - Alert title
     * @param {string} text - Alert text (optional)
     * @param {object} options - Additional options
     */
    error(title, text = null, options = {}) {
        return Swal.fire({
            ...this.defaultConfig,
            icon: 'error',
            title: title,
            text: text,
            confirmButtonText: 'OK',
            customClass: {
                ...this.defaultConfig.customClass,
                confirmButton: 'bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500'
            },
            ...options
        });
    }

    /**
     * Warning alert
     * @param {string} title - Alert title
     * @param {string} text - Alert text (optional)
     * @param {object} options - Additional options
     */
    warning(title, text = null, options = {}) {
        return Swal.fire({
            ...this.defaultConfig,
            icon: 'warning',
            title: title,
            text: text,
            confirmButtonText: 'OK',
            customClass: {
                ...this.defaultConfig.customClass,
                confirmButton: 'bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500'
            },
            ...options
        });
    }

    /**
     * Info alert
     * @param {string} title - Alert title
     * @param {string} text - Alert text (optional)
     * @param {object} options - Additional options
     */
    info(title, text = null, options = {}) {
        return Swal.fire({
            ...this.defaultConfig,
            icon: 'info',
            title: title,
            text: text,
            confirmButtonText: 'OK',
            ...options
        });
    }

    /**
     * Confirmation dialog
     * @param {string} title - Alert title
     * @param {string} text - Alert text
     * @param {object} options - Additional options
     */
    confirm(title, text = null, options = {}) {
        const defaultOptions = {
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sim, confirmar!',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        };

        return Swal.fire({
            ...this.defaultConfig,
            ...defaultOptions,
            customClass: {
                ...this.defaultConfig.customClass,
                confirmButton: 'bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500'
            },
            ...options
        });
    }

    /**
     * Delete confirmation dialog
     * @param {string} itemName - Name of item to delete (optional)
     * @param {object} options - Additional options
     */
    confirmDelete(itemName = 'este item', options = {}) {
        return this.confirm(
            'Tem certeza?',
            `Você realmente deseja excluir ${itemName}? Esta ação não pode ser desfeita.`,
            {
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar',
                ...options
            }
        );
    }

    /**
     * Toast notification
     * @param {string} type - Type: success, error, warning, info
     * @param {string} title - Toast title
     * @param {object} options - Additional options
     */
    toast(type, title, options = {}) {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            },
            customClass: {
                popup: 'rounded-lg shadow-lg'
            }
        });

        return Toast.fire({
            icon: type,
            title: title,
            ...options
        });
    }

    /**
     * Loading dialog
     * @param {string} title - Loading title
     * @param {string} text - Loading text (optional)
     */
    loading(title = 'Processando...', text = null) {
        return Swal.fire({
            ...this.defaultConfig,
            title: title,
            text: text,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }

    /**
     * Close any open SweetAlert
     */
    close() {
        Swal.close();
    }

    /**
     * Custom alert with HTML content
     * @param {string} title - Alert title
     * @param {string} html - HTML content
     * @param {object} options - Additional options
     */
    custom(title, html, options = {}) {
        return Swal.fire({
            ...this.defaultConfig,
            title: title,
            html: html,
            ...options
        });
    }
}

// Initialize and make available globally
window.SweetAlert = new SweetAlertConfig();

// Backward compatibility aliases
window.showSuccessAlert = (title, text, options) => window.SweetAlert.success(title, text, options);
window.showErrorAlert = (title, text, options) => window.SweetAlert.error(title, text, options);
window.showWarningAlert = (title, text, options) => window.SweetAlert.warning(title, text, options);
window.showInfoAlert = (title, text, options) => window.SweetAlert.info(title, text, options);
window.showConfirmAlert = (title, text, options) => window.SweetAlert.confirm(title, text, options);
window.showDeleteConfirm = (itemName, options) => window.SweetAlert.confirmDelete(itemName, options);
window.showToast = (type, title, options) => window.SweetAlert.toast(type, title, options);
window.showLoading = (title, text) => window.SweetAlert.loading(title, text);