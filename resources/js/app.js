import Quill from 'quill';

const toolbarOptions = [
    [{ header: [2, 3, false] }],
    ['bold', 'italic', 'underline', 'strike'],
    [{ color: [] }, { background: [] }],
    [{ align: [] }],
    ['blockquote', 'code-block'],
    [{ list: 'ordered' }, { list: 'bullet' }, { list: 'check' }],
    ['link', 'image'],
    ['clean'],
];

document.querySelectorAll('[data-editor-wrapper]').forEach((wrapperEl) => {
    const textarea = wrapperEl.querySelector('[data-editor-target]');
    const mountEl = wrapperEl.querySelector('[data-editor]');
    const charCountEl = wrapperEl.querySelector('[data-char-count]');
    const uploadUrl = wrapperEl.dataset.uploadUrl;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    const quill = new Quill(mountEl, {
        theme: 'snow',
        modules: {
            toolbar: {
                container: toolbarOptions,
                handlers: {
                    image: imageUploadHandler,
                },
            },
        },
        placeholder: 'Start writing your post…',
    });

    // Populate editor with existing content (edit form)
    if (textarea.value) {
        const delta = quill.clipboard.convert({ html: textarea.value });
        quill.setContents(delta, 'silent');
    }

    // Sync to hidden textarea on every change
    quill.on('text-change', () => {
        textarea.value = quill.getSemanticHTML();
        if (charCountEl) {
            const len = quill.getLength() - 1; // -1 for trailing newline
            charCountEl.textContent = `${Math.max(0, len)} characters`;
        }
    });

    // Initial char count
    if (charCountEl) {
        const len = Math.max(0, quill.getLength() - 1);
        charCountEl.textContent = `${len} characters`;
    }

    function imageUploadHandler() {
        const input = document.createElement('input');
        input.type = 'file';
        input.accept = 'image/*';
        input.click();
        input.addEventListener('change', async () => {
            const file = input.files[0];
            if (!file) return;
            const fd = new FormData();
            fd.append('image', file);
            try {
                const res = await fetch(uploadUrl, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    body: fd,
                });
                const { url } = await res.json();
                const range = quill.getSelection(true);
                quill.insertEmbed(range.index, 'image', url);
                quill.setSelection(range.index + 1);
            } catch {
                alert('Image upload failed. Please try again.');
            }
        });
    }
});
