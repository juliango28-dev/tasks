/**
 * Funciones JavaScript principales
 * Archivo: public/js/main.js
 */

// Validar formulario en cliente
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('taskForm');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Limpiar errores previos
            document.querySelectorAll('.error-message').forEach(el => {
                el.textContent = '';
            });
            
            // Validar título
            const titleInput = document.getElementById('title');
            if (!titleInput.value.trim()) {
                document.getElementById('titleError').textContent = 'El título es obligatorio';
                isValid = false;
            }
            
            // Validar responsable
            const userSelect = document.getElementById('user_id');
            if (!userSelect.value) {
                document.getElementById('userError').textContent = 'Debe seleccionar un responsable';
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    }
    
    // Mostrar mensajes de estado
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('msg') === 'success') {
        showNotification('Tarea creada exitosamente', 'success');
    } else if (urlParams.get('msg') === 'updated') {
        showNotification('Tarea actualizada exitosamente', 'success');
    } else if (urlParams.get('msg') === 'deleted') {
        showNotification('Tarea eliminada exitosamente', 'success');
    }
});

/**
 * Mostrar notificación temporal
 * @param {string} message Mensaje a mostrar
 * @param {string} type Tipo de notificación (success, error)
 */
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type}`;
    notification.textContent = message;
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.zIndex = '1000';
    notification.style.animation = 'slideIn 0.3s ease';
    
    document.body.appendChild(notification);
    
    // Remover después de 3 segundos
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Animaciones
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);