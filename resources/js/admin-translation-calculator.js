// Mini calculadora para el panel de administración de traducciones
// Calcula el total aproximado: total = palabras * precio_por_palabra

function initTranslationCalculator() {
  // Verificar si estamos en la página correcta
  const el = document.getElementById('translation-calculator');
  if (!el) return;

  // Obtener elementos del DOM
  const wordsInput = document.getElementById('calc-words');
  const priceInput = document.getElementById('calc-price');
  const resultEl = document.getElementById('calc-result');
  const resetBtn = document.getElementById('calc-reset');

  // Formatear número como moneda en euros
  const formatCurrency = (value) => new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(value);

  // Calcular y mostrar el total
  const compute = () => {
    const words = Math.max(0, Number(wordsInput.value) || 0);
    const price = Math.max(0, Number(priceInput.value) || 0);
    const total = words * price;
    resultEl.textContent = formatCurrency(total);
  };

  // Agregar listeners para recalcular al cambiar inputs
  [wordsInput, priceInput].forEach(input => input.addEventListener('input', compute));

  // Reset a valores por defecto y recalcular
  resetBtn.addEventListener('click', () => {
    wordsInput.value = 0;
    priceInput.value = '0.10';
    compute();
  });

  // Calcular inicialmente
  compute();
}

// Inicializar cuando el DOM esté listo
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initTranslationCalculator);
} else {
  initTranslationCalculator();
}
