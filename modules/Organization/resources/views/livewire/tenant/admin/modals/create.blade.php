<div aria-hidden="true" aria-labelledby="add_organization_label" class="modal fade action-sheet" data-bs-backdrop="static"
    id="add-organization" role="dialog" tabindex="-1" wire:ignore.self>
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="add_organization_label">
                    <i aria-hidden="true" class="fa fa-desktop"></i>
                    {{ __($form->editing ? 'Edit Organization' : 'Create Organization') }}
                </h5>
            </div>

            <form class="form" wire:submit="store">
                <div class="modal-body">
                    <x-alert />

                    <div class="action-sheet-content">
                        {{-- Name --}}
                        <div class="form-group basic">
                            <div class="input-wrapper">
                                <label class="label" for="name">{{ __('Name') }}</label>
                                <input class="form-control" id="name" name="name"
                                    placeholder="{{ __('Name') }}" required type="text"
                                    wire:model.change="form.name" />

                                <x-input.error :name="'form.name'" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal" type="button" wire:click="close()"
                        wire:loading.attr="disabled">
                        <i aria-hidden="true" class="fa fa-times-circle me-1"></i>
                        {{ __('Cancel') }}
                    </button>

                    <button class="btn btn-primary" type="submit" wire:loading.attr="disabled">
                        <i aria-hidden="true" class="fa fa-paper-plane me-1"></i>
                        {{ __($form->editing ? 'Update' : 'Submit') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
