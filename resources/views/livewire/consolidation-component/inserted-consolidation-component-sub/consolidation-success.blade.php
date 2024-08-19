<div>
    <div wire:ignore.self class="modal dark" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
        data-bs-theme="light" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl  modal-dialog-scrollable" role="document">
            <div class="modal-content">
                <div style="background-color:#1a2035;" class="modal-header">
                    <h6 class="modal-title font-weight-normal" id="exampleModalLabel">Please check your consolidated
                        data carefuly!</h6>
                    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div style="padding:-5px; background-color:#222941; " class="modal-body">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0 table-borderless">
                            <thead style="background-color:#222941; ">
                                <tr>
                                    <th
                                        class=" text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ">
                                        EACODE</th>
                                    <th
                                        class=" text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ">
                                        HCN</th>
                                    <th
                                        class="  text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ">
                                        SHSN</th>
                                    <th
                                        class=" text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ">
                                        Household Head</th>
                                    <th
                                        class=" text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        AREA NAME</th>
                                </tr>
                            </thead>
                            <tbody style="background-color:#222941; ">
                                @foreach ($ConsolidatedData as $consolidated_data)
                                    <tr>
                                        <td>
                                            <p class="text-xs text-secondary mb-0  text-center">
                                                {{ $consolidated_data->eacode }}
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-xs text-secondary mb-0 text-center">
                                                {{ $consolidated_data->hcn }}</p>
                                        </td>
                                        <td>
                                            <p class="text-xs text-secondary mb-0 text-center">
                                                {{ $consolidated_data->shsn }}
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-xs text-secondary mb-0  text-center">
                                                {{ $consolidated_data->hhead }}
                                            </p>
                                        </td>
                                        <td>
                                            <p class="text-xs text-secondary mb-0  text-center">
                                                {{ $consolidated_data->areaname }}</p>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
                <div style="background-color:#1a2035;" class="modal-footer">
                    <button type="button" class="btn bg-gradient-secondary close-modal-button"
                        data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn bg-gradient-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        window.addEventListener('success-consolidation-open-modal', function() {
            var myModal = new bootstrap.Modal(document.getElementById('staticBackdrop'));
            myModal.show();
        });

        window.addEventListener('beforeunload', function(event) {
            // Log a message to the console
            Livewire.dispatch('reload-delete');

            // Optional: You can also provide a custom message to the user
            // event.returnValue = 'Are you sure you want to leave?';
        });
    </script>

</div>
