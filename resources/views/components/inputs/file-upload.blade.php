<div
     wire:key="2098"
     x-data="{
       totalSize: 0,
       isUploading: false,
       progress: 0,
      getTotalSize: function() {return `(${this.totalSize}mb)`},
      getProgress: function () {return `(${this.progress}%)`},
      }"
     x-on:livewire-upload-start="isUploading = true"
     x-on:livewire-upload-finish="isUploading = false"
     x-on:livewire-upload-error="isUploading = false"
     x-on:livewire-upload-progress="progress = $event.detail.progress"
     class="{{$attributes->get('wrapperClass')}}"
>
    <div class="mb-3">
        <div class="flex justify-between">
            <label
                for="{{$attributes->get('id') ?? 'formFile'}}"
                class="mb-2 inline-block text-neutral-700 dark:text-neutral-200"
            >Upload your files max (25mb)</label
            >
            <p x-show="parseFloat(totalSize) > 0" x-text="getTotalSize()"></p>
        </div>

        <input
            x-show="! isUploading"
            x-transition
            x-ref="fileUpload"
            @change="
                         fileList = $refs.fileUpload.files;
                         totalSize = 0;

                         for(file of fileList) {
                            totalSize += file.size;
                         }

                         totalSize = (totalSize/ Math.pow(10, 6)).toFixed(2);
                    "
            {{$attributes->merge(['class' => 'relative m-0 block w-full h-32 min-w-0 flex-auto rounded border border-solid border-neutral-300 bg-clip-padding px-3
                      py-[0.32rem] text-base font-normal text-neutral-700 transition duration-300 ease-in-out file:-mx-3 file:-my-[0.32rem]
                      file:overflow-hidden file:rounded-none file:border-0 file:border-solid file:border-inherit file:bg-neutral-100 file:px-3
                      file:py-[0.32rem] file:text-neutral-700 file:transition file:duration-150 file:ease-in-out file:[border-inline-end-width:1px]
                      file:[margin-inline-end:0.75rem] hover:file:bg-neutral-200 focus:border-primary focus:text-neutral-700 focus:shadow-te-primary
                      focus:outline-none dark:border-neutral-600 dark:text-neutral-200 dark:file:bg-neutral-700 dark:file:text-neutral-100 dark:focus:border-primary'])
                      ->except(['wrapperClass'])}}
            type="file"
            id="formFile"
            multiple
        />
        <div x-cloak x-show="isUploading">
            <progress max="100" x-bind:value="progress" class="w-full"></progress>
            <p class="text-center" x-text="getProgress()"></p>
        </div>
    </div>
</div>
