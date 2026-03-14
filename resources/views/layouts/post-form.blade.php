<div class="space-y-3">
    <label for="title" class="block text-lg font-medium text-slate-700">Title</label>
    <input
        id="title"
        name="title"
        type="text"
        value="{{ old('title', $titleValue ?? '') }}"
        class="block w-full rounded-md border border-slate-300 px-4 py-3 text-base text-slate-800 outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100"
    >
    @error('title')
        <p class="text-sm text-rose-600">{{ $message }}</p>
    @enderror
</div>

<div class="space-y-3">
    <label for="description" class="block text-lg font-medium text-slate-700">Description</label>
    <textarea
        id="description"
        name="description"
        rows="5"
        class="block w-full rounded-md border border-slate-300 px-4 py-3 text-base text-slate-800 outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100"
    >{{ old('description', $descriptionValue ?? '') }}</textarea>
    @error('description')
        <p class="text-sm text-rose-600">{{ $message }}</p>
    @enderror
</div>

<div class="space-y-3">
    <label for="creator" class="block text-lg font-medium text-slate-700">Post Creator</label>
    <select
        id="creator"
        name="creator"
        class="block w-full rounded-md border border-slate-300 px-4 py-3 text-base text-slate-800 outline-none transition focus:border-sky-500 focus:ring-4 focus:ring-sky-100"
    >
        @foreach ($creators as $creator)
            <option value="{{ $creator->id }}" @selected((string) old('creator', $creatorValue ?? '') === (string) $creator->id)>{{ $creator->name }}</option>
        @endforeach
    </select>
    @error('creator')
        <p class="text-sm text-rose-600">{{ $message }}</p>
    @enderror
</div>

<div class="space-y-3">
    <label for="image" class="block text-lg font-medium text-slate-700">Post Image</label>
    @if (!empty($imageUrl))
        <img
            src="{{ $imageUrl }}"
            alt="{{ $titleValue ?: 'Post image' }}"
            class="h-48 w-full rounded-md object-cover"
        >
    @endif
    <input
        id="image"
        name="image"
        type="file"
        accept=".jpg,.png"
        class="block w-full rounded-md border border-slate-300 px-4 py-3 text-base text-slate-800 file:mr-4 file:rounded-md file:border-0 file:bg-slate-100 file:px-4 file:py-2 file:text-sm file:font-medium file:text-slate-700"
    >
    @error('image')
        <p class="text-sm text-rose-600">{{ $message }}</p>
    @enderror
</div>
