<div>
    <div class="main-div d-flex justify-content-center align-items-center min-vh-70">
        <div class="container d-flex justify-content-center">
            <div class="row">
                <div class="col-md-12">
                    <div class="consolidation-drop-area">
                        <span class="choose-consolidation-button">Choose files</span>
                        @if ($ConsolidationFile)
                            <span class="consolidation-message">{{ $ConsolidationFile->getClientOriginalName() }}</span>
                        @else
                            <span class="consolidation-message">or drag and drop files here</span>
                        @endif
                        <form wire:submit="ConsolidationSave">
                            <input wire:model='ConsolidationFile' class="consolidation-input" type="file">
                            @error('ConsolidationFile')
                                <div class="position-fixed bottom-1 end-1 z-index-2">
                                    <div class="toast fade show p-2 bg-white" role="alert" aria-live="assertive"
                                        aria-atomic="true" id="errorToast">
                                        <div class="toast-header border-0">
                                            <i class="material-icons text-danger me-2">
                                                error
                                            </i>
                                            <span class="me-auto font-weight-bold">Error</span>
                                            <i class="fas fa-times text-md ms-3 cursor-pointer" data-bs-dismiss="toast"
                                                aria-label="Close"></i>
                                        </div>
                                        <hr class="horizontal dark m-0">
                                        <div class="toast-body">
                                            {{ $message }}
                                        </div>
                                    </div>
                                </div>
                            @enderror
                    </div>
                    <button type="submit" class="btn btn-lg w-100 btn-primary mt-2">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        @if ($errors->has('ConsolidationFile'))
            const toastElement = document.getElementById('errorToast');
            if (toastElement) {
                const toast = new bootstrap.Toast(toastElement);
                toast.show();
            }
        @endif
    </script>
</div>
