// Mini calculadora para el panel de administración de traducciones
// Calcula el total aproximado: total = palabras * precio_por_palabra

// Función principal que inicializa el comportamiento del calculador
function initTranslationCalculator() {
  // Elemento contenedor: si no existe, salimos (no estamos en la página admin correspondiente)
  const el = document.getElementById('translation-calculator');
  if (!el) {
    // No estamos en la página del admin translations
    return;
  }

  // Campos y elementos del DOM que usa la calculadora
  const wordsInput = document.getElementById('calc-words');   // input número de palabras
  const priceInput = document.getElementById('calc-price');   // input precio por palabra
  const resultEl = document.getElementById('calc-result');    // elemento donde mostramos el resultado
  const resetBtn = document.getElementById('calc-reset');     // botón para restaurar valores por defecto

  // Formatea un número como moneda en euros con la locale española.
  // Si Intl falla, cae a un formato sencillo.
  function formatCurrency(value) {
    try {
      return new Intl.NumberFormat('es-ES', { style: 'currency', currency: 'EUR' }).format(value);
    } catch (e) {
      return '€' + Number(value).toFixed(2);
    }
  }

  // Calcula el total y lo muestra en pantalla.
  // Validaciones simples: no permitimos valores negativos.
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

  // Escuchadores: recalcular al cambiar cualquiera de los inputs
  wordsInput.addEventListener('input', compute);
  priceInput.addEventListener('input', compute);

  // Reset: vuelve a valores por defecto y fuerza un recálculo
  resetBtn.addEventListener('click', () => {
    wordsInput.value = 0;
    // Precio por palabra por defecto (ejemplo)
    priceInput.value = '0.10';
    compute();
  });

  // Inicializar con el cálculo actual (por si hay valores prellenados)
  compute();
}

// Ejecutar la inicialización cuando el DOM esté listo
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initTranslationCalculator);
} else {
  initTranslationCalculator();
}
