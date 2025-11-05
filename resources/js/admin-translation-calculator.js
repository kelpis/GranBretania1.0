// Mini calculadora para el panel de administración de traducciones
// Calcula: total = palabras * precio_por_palabra

function initTranslationCalculator() {
  const el = document.getElementById('translation-calculator');
  if (!el) {
    // No estamos en la página del admin translations
    return;
  }

  const wordsInput = document.getElementById('calc-words');
  const priceInput = document.getElementById('calc-price');
  const resultEl = document.getElementById('calc-result');
  const resetBtn = document.getElementById('calc-reset');

  function formatCurrency(value) {
    try {
      return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(value);
    } catch (e) {
      return '€' + Number(value).toFixed(2);
    }
  }

  function compute() {
    const words = Number(wordsInput.value) || 0;
    const price = Number(priceInput.value) || 0;
    if (words < 0 || price < 0) {
      resultEl.textContent = 'Valores inválidos';
      return;
    }
    const total = words * price;
    resultEl.textContent = formatCurrency(total);
  }

  wordsInput.addEventListener('input', compute);
  priceInput.addEventListener('input', compute);
  resetBtn.addEventListener('click', () => {
    wordsInput.value = 0;
    priceInput.value = '0.10';
    compute();
  });

  // Inicializar
  compute();
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initTranslationCalculator);
} else {
  initTranslationCalculator();
}
