(() => {
  'use strict';
  document.addEventListener('click', event => {
    const add = event.target.closest('[data-add-row]');
    if (add) {
      const repeater = add.closest('[data-repeater]');
      const template = repeater.querySelector('[data-row-template]');
      const index = Date.now().toString(36) + Math.floor(Math.random() * 1000);
      repeater.querySelector('[data-repeater-rows]').insertAdjacentHTML('beforeend', template.innerHTML.replaceAll('__INDEX__', index));
    }
    const remove = event.target.closest('[data-remove-row]');
    if (remove) remove.closest('.es-repeat-row').remove();
    const addFaq = event.target.closest('[data-add-faq]');
    if (addFaq) {
      const parent = document.querySelector('[data-faq-rows]');
      if (!parent) return;
      const i = Date.now().toString(36);
      const row = document.createElement('div'); row.className = 'es-repeat-row';
      ['question','answer'].forEach((key) => {
        const input = document.createElement('input'); input.name = `es_meta[_es_faq_schema_repeater][${i}][${key}]`;
        input.placeholder = key === 'question' ? 'سؤال' : 'پاسخ'; row.appendChild(input);
      });
      const del = document.createElement('button'); del.type = 'button'; del.className = 'button'; del.dataset.removeRow = ''; del.textContent = 'حذف'; row.appendChild(del); parent.appendChild(row);
    }
    const choose = event.target.closest('[data-option-image],[data-media-target]');
    if (choose && window.wp && wp.media) {
      const id = choose.dataset.optionImage || choose.dataset.mediaTarget;
      const target = document.getElementById(id); if (!target) return;
      const multiple = choose.dataset.multiple === '1';
      const frame = wp.media({ title: 'انتخاب رسانه', button: { text: 'انتخاب' }, multiple });
      frame.on('select', () => {
        const selected = frame.state().get('selection').toJSON();
        target.value = selected.map(item => item.id).join(',');
        const preview = target.closest('.es-image-field')?.querySelector('img');
        if (preview && selected[0]) { preview.src = selected[0].sizes?.medium?.url || selected[0].url; preview.hidden = false; }
      }); frame.open();
    }
    const clear = event.target.closest('[data-clear-image]');
    if (clear) { const input = document.getElementById(clear.dataset.clearImage); if (input) input.value = ''; const image = clear.closest('.es-image-field')?.querySelector('img'); if (image) image.hidden = true; }
  });
})();
