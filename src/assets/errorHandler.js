const csrfParam = document.querySelector('meta[name="csrf-param"]').getAttribute('content');
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

window.onerror = function(message, source, lineno, colno, error) {
    const formData = new FormData();
    formData.append('message', message);
    formData.append('source', source);
    formData.append('lineno', lineno);
    formData.append('colno', colno);
    formData.append('error', error ? error.stack : '');
    formData.append(csrfParam, csrfToken);

    // Invio degli errori al backend tramite AJAX
    fetch(window.location.href, {
       method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },        
        body: formData,
    }).then(() => {
        console.log('Errore ricevuto e registrato (nessuna risposta prevista)');
    }).catch(err => {
        console.error('Errore durante l\'invio dell\'errore:', err);
    });

};