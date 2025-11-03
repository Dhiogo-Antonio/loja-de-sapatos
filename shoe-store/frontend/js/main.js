
window.onload = function() {
  // Verifica se existe a notificação
  if (document.querySelector('.notification')) {
    // Exibe a notificação
    document.querySelector('.notification').classList.add('show');

    // Remove a notificação após 3 segundos
    setTimeout(function() {
      document.querySelector('.notification').classList.remove('show');
    }, 3000); // 3000ms = 3 segundos
  }
};

