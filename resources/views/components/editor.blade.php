@props(['name' => 'content', 'value' => ''])

<div data-editor-wrapper data-upload-url="{{ route('upload.image') }}">
    {{-- Hidden textarea synced by Quill on every change --}}
    <textarea data-editor-target name="{{ $name }}" class="sr-only" aria-hidden="true">{{ old($name, $value) }}</textarea>

    {{-- Quill mounts its toolbar + editable area into this div --}}
    <div data-editor class="min-h-64"></div>

    <p class="mt-1 text-xs text-gray-400" data-char-count></p>
</div>
