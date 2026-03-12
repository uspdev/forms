
// Helper para facilitar a integração do Select2 com os formulários da USP, especialmente em modais.
// Função auto executável que define o namespace window.uspdevFormsSelect2 com métodos para inicializar campos Select2
(function(window, document) {
  if (window.uspdevFormsSelect2) {
    return;
  }

  function waitForSelect2(onReady) {
    var attempts = 1;
    var maxAttempts = 50;

    var intervalId = setInterval(function() {
      if (window.jQuery && window.jQuery.fn && window.jQuery.fn.select2) {
        // Select2 está disponível -> segue para inicializar
        clearInterval(intervalId);
        onReady();
      } else if (attempts >= maxAttempts) {
        // Após várias tentativas, ainda não encontrou o Select2 -> para de tentar
        clearInterval(intervalId);
        console.error('jQuery/Select2 nao carregou apos varias tentativas.');
      }
      attempts++;
    }, 100);
  }

  function focusSearchField() {
    var searchField = document.querySelector('.select2-container--open .select2-search__field');
    if (searchField) {
      searchField.focus();
    }
  }
  // Configura e inicializa o Select2 em um campo específico, com opções personalizadas
  function init(config) {
    var $ = window.jQuery;
    var $select = $(config.selector);

    if (!$select.length) {
      return;
    }

    var $modalParent = $select.closest('.modal');

    if ($select.data('select2')) {
      $select.select2('destroy');
    }

    var options = {
      ajax: {
        url: config.url,
        dataType: 'json',
        delay: 1000
      },
      allowClear: true,
      placeholder: config.placeholder,
      minimumInputLength: config.minimumInputLength,
      theme: 'bootstrap4',
      width: 'resolve',
      language: 'pt-BR',
      // Garante que o dropdown do Select2 seja renderizado dentro do modal, evitando problemas de sobreposição
      dropdownParent: $modalParent.length ? $modalParent : $(document.body)
    };
    // Permite que o chamador forneça uma função customizada para processar os resultados da requisição AJAX, se necessário
    // Patrimonio-usp precisa
    if (typeof config.processResults === 'function') {
      options.ajax.processResults = config.processResults;
    }

    $select.select2(options);

    // Configura o evento para focar o campo de busca do Select2 quando o dropdown for aberto
    $select
      .off('select2:open.' )
      .on('select2:open.', focusSearchField);
  }

  function initOnLoad(config) {
    // Aguarda o carregamento do Select2 antes de tentar inicializar os campos, garantindo que a biblioteca esteja pronta para uso
    function schedule() {
      waitForSelect2(function() {
        init(config);
      });
    }
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', schedule);
    } else {
      schedule();
    }
  }
  // Expõe os métodos initOnLoad e init no namespace window.uspdevFormsSelect2 para uso em outros scripts
  window.uspdevFormsSelect2 = {
    initOnLoad: initOnLoad,
    init: init
  };
})(window, document);
