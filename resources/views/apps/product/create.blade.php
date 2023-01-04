@extends('layout.default')

{{-- Styles Section --}}
@section('styles')
    <link href="{{ asset('public/assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('public/assets/css/summernote/dist/summernote-bs4.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('public/assets/fine-uploader/fine-uploader-gallery.css') }}" rel="stylesheet">

    <style>
        .table-bg tbody tr{
            background-color: #EBEDF3;
        }
    </style>
@endsection

@section('content')
<form action="{{url('app/product/save')}}" method="post" autocomplete="off" id="form_account" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header bg-pro">
                    <h3 class="card-title color-white">Add Product</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="row">
                                
                                <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                    <label class="col-form-label">{{__('labels.ProductsName')}} <span class="error">*</span></label>
                                    <input  type="text" name="product_name" id="product_name" class="form-control" value="{{old('product_name')}}" maxlength="50" autocomplete="new-product_name" required="">
                                    @error('product_name') <span class="error">{{$message}}</span> @enderror
                                </div>

                                <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                    <label class="col-form-label">{{__('labels.ProductDescription')}} <span class="info_span">
                                        <i class="fa fa-info-circle" aria-hidden="true" style="font-size: 20px;padding-right: 18px;text-align: left" data-html="true" data-toggle="tooltip" data-placement="right" title="" data-original-title="We suggest a limit of 1100 characters for optimal user experience. Please note images and external links will not render on the POS"></i>
                                    </span> </label>
                                    <textarea class="summernote" id="product_des" name="product_des">{{old('product_des')}}</textarea>
                                </div>
                                
                            </div>
                        </div>

                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"> 
                            <div class="row">
                                
                                <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                    <div class="mb-0 table-responsive">
                                        <table class="table table-bordered table-striped table-bg" width="100%">
                                            <thead>
                                                <tr class="bg-primary">
                                                    <th style="font-weight: 500;color: #ffff;">{{__('labels.ProductIdentifier')}}</th>
                                                    <th style="font-weight: 500;color: #ffff;">{{__('labels.ProductVariantType')}}</th>
                                                    <th style="font-weight: 500;color: #ffff;">{{__('labels.ProductVariantName')}}</th>
                                                    <th style="font-weight: 500;color: #ffff;">{{__('labels.ProductVariantPrice')}}</th>
                                                    <th style="font-weight: 500;color: #ffff;">{{__('labels.ColAction')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody id="addVarintRow">
                                                <tr>
                                                    <td>
                                                        <div class="form-group mb-0">

                                                            <input type="text" pattern="[A-Za-z0-9/s]{1,15}" class="form-control old1 product_identifier" value="" name="product_identifier[]" id="product_identifier1" >

                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group mb-0">
                                                            <input type="text" class="form-control" value="generic" name="variant_type[]" placeholder="generic" required="">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group mb-0">
                                                            <input type="text" class="form-control" value="none" name="variant_name[]" required="">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group mb-0">
                                                            <input type="number" class="form-control numeric inMachine" min="0" value="" name="price[]" required="" step=".01">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group mb-0">
                                                            <a data-variant-id="0" class="btn btn-sm btn-primary btn-text-primary btn-icon deleteField" href="javascript:"><i class='fas fa-trash-alt fsize13'></i></a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    
                                    <a class="btn btn-primary" onclick="AddDateTime()">{{__('labels.ProductVariantAdd')}}</a>
                                </div>

                                <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                    <div>
                                        <label style="font-size: 14px;">
                                            <span style='color:navy;font-weight:bold'>{{__('labels.ProductMainImage')}}:<span class="error">*</span></span>
                                            <span class="info_span">
                                                <i class="fa fa-info-circle" aria-hidden="true" style="font-size: 20px;padding-right: 18px;text-align: left" data-html="true" data-toggle="tooltip" data-placement="right" title="" data-original-title="Ideal product image dimensions are 600x400."></i>
                                            </span>
                                        </label>
                                        <input type="hidden" name="file" id="new_file" value="" />
                                        <input type="hidden" name="filename" id="new_filename" value="" />
                                        <span id="image-error" class=""></span>

                                        <div id="fine-uploader-gallery"></div>
                                    </div>
                                   
                                </div>

                                <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                    <div>
                                        <label style="font-size: 14px;">
                                            <span style='color:navy;font-weight:bold'>{{__('labels.ProductImage')}}:</span>
                                            <span class="info_span">
                                                <i class="fa fa-info-circle" aria-hidden="true" style="font-size: 20px;padding-right: 18px;text-align: left" data-html="true" data-toggle="tooltip" data-placement="right" title="" data-original-title="Ideal product image dimensions are 600x400."></i>
                                            </span>
                                        </label>
                                        <input type="hidden" name="new_file_mult" id="new_file_mult" value="" />
                                        <input type="hidden" name="new_filename_mult" id="new_filename_mult" value="" />
                                        
                                        <div id="fine-uploader-gallery1"></div>
                                    </div>
                                   
                                </div>
                                
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 text-center">
                        <button type="submit" class="btn btn-primary">{{__('labels.SAVE')}}</button>
                        <a href="{{ url('app/products') }}"><button type="button" class="btn btn-danger">{{__('labels.CANCEL')}}</button></a>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</form>

<input type="hidden" value="{{ Config::get('constants.ProductVariantDelete') }}" id="variantDeleteMsg" />
@endsection

@section('scripts')

    <script src="{{ asset('public/assets/js/pages/crud/forms/editors/summernote.js') }}" type="text/javascript"></script>
    <script src="{{ asset('public/assets/fine-uploader/fine-uploader.js') }}" type="text/javascript"></script>
    <script src="{{ asset('js/account/products.js') }}?v={{ config('constants.WEB_VERSION') }}" type="text/javascript"></script>

    <script type="text/template" id="qq-template-gallery">
        <div class="qq-uploader-selector qq-uploader qq-gallery" qq-drop-area-text="Drop files here">
            <div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">
                <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>
            </div>
            <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
                <span class="qq-upload-drop-area-text-selector"></span>
            </div>
            <div class="qq-upload-button-selector qq-upload-button">
                <div>Select files</div>
            </div>
            <span class="qq-drop-processing-selector qq-drop-processing">
                <span>Processing dropped files...</span>
                <span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
            </span>
            <ul class="qq-upload-list-selector qq-upload-list" role="region" aria-live="polite" aria-relevant="additions removals">
                <li>
                    <span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
                    <div class="qq-progress-bar-container-selector qq-progress-bar-container">
                        <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
                    </div>
                    <span class="qq-upload-spinner-selector qq-upload-spinner"></span>
                    <div class="qq-thumbnail-wrapper">
                        <img class="qq-thumbnail-selector" qq-max-size="120" qq-server-scale>
                    </div>
                    <button type="button" class="qq-upload-cancel-selector qq-upload-cancel">X</button>
                    <button type="button" class="qq-upload-retry-selector qq-upload-retry">
                        <span class="qq-btn qq-retry-icon" aria-label="Retry"></span>
                        Retry
                    </button>

                    <div class="qq-file-info">
                        <div class="qq-file-name">
                            <span class="qq-upload-file-selector qq-upload-file"></span>
                            <span class="qq-edit-filename-icon-selector qq-edit-filename-icon" aria-label="Edit filename"></span>
                        </div>
                        <input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
                        <span class="qq-upload-size-selector qq-upload-size"></span>
                        <button type="button" class="qq-btn qq-upload-delete-selector qq-upload-delete">
                            <span class="qq-btn qq-delete-icon" aria-label="Delete"></span>
                        </button>
                        <button type="button" class="qq-btn qq-upload-pause-selector qq-upload-pause">
                            <span class="qq-btn qq-pause-icon" aria-label="Pause"></span>
                        </button>
                        <button type="button" class="qq-btn qq-upload-continue-selector qq-upload-continue">
                            <span class="qq-btn qq-continue-icon" aria-label="Continue"></span>
                        </button>
                    </div>
                </li>
            </ul>

            <dialog class="qq-alert-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <div class="qq-dialog-buttons">
                    <button type="button" class="qq-cancel-button-selector">Close</button>
                </div>
            </dialog>

            <dialog class="qq-confirm-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <div class="qq-dialog-buttons">
                    <button type="button" class="qq-cancel-button-selector">No</button>
                    <button type="button" class="qq-ok-button-selector">Yes</button>
                </div>
            </dialog>

            <dialog class="qq-prompt-dialog-selector">
                <div class="qq-dialog-message-selector"></div>
                <input type="text">
                <div class="qq-dialog-buttons">
                    <button type="button" class="qq-cancel-button-selector">Cancel</button>
                    <button type="button" class="qq-ok-button-selector">Ok</button>
                </div>
            </dialog>
        </div>
    </script>

    <script type="text/javascript">

        var galleryUploader = new qq.FineUploader({
            element: document.getElementById("fine-uploader-gallery"),
            template: 'qq-template-gallery',
            request: {
                endpoint: base_url+"/app/common/fileUpload",
                params: {
                    _token : jQuery("input[name='_token']").val()
                }
            },
            deleteFile: {
                enabled     : true,
                forceConfirm: true,
                endpoint: base_url+"/app/common/deleteUpload",
                params  : {
                    _token  : jQuery("input[name='_token']").val(),
                }
            },
            thumbnails: {
                placeholders: {
                    waitingPath: baseurl+'/public/assets/fine-uploader/placeholders/waiting-generic.png',
                    notAvailablePath: baseurl+'/public/assets/fine-uploader/placeholders/not_available-generic.png'
                }
            },
            multiple: false,
            autoUpload: true,
            validation: {
                itemLimit: 1,
                acceptFiles: 'image/*',
                allowedExtensions: ['jpeg', 'jpg', 'gif', 'png']
            },
            callbacks: {
                onError: function(id, name, errorReason, xhrOrXdr) {
                    alert(qq.format("Error on file number {} - {}.  Reason: {}", id, name, errorReason));
                },
                onSubmit: function(event, id, fileName, responseJSON) {
                  
                },
                onComplete: function(id, fileName, response, xhr) {
                    if (response.success) {
                        jQuery('#new_file').val(response.location);                        
                        jQuery('#new_filename').val(response.locationName);
                    }
                },
                onSubmitDelete: function(id) {
                    this.setDeleteFileParams({
                        _token : jQuery("input[name='_token']").val(),
                        filepath: jQuery("input[name='file']").val(),
                        filename: jQuery("input[name='filename']").val(),
                    }, id);
                },
                onDeleteComplete: function(id, fileName, response, xhr) {
                    if (response.success) {
                        jQuery('#new_file').val('');                        
                        jQuery('#new_filename').val('');
                    }
                },          
            }            
        }); 
        
        var jsonLocation        = [];
        var jsonLocationPath    = [];
        var galleryUploader = new qq.FineUploader({
            element: document.getElementById("fine-uploader-gallery1"),
            template: 'qq-template-gallery',
            request: {
                endpoint: base_url+"/app/common/fileUpload",
                params: {
                    _token : jQuery("input[name='_token']").val()
                }
            },
            // deleteFile: {
            //     enabled     : true,
            //     forceConfirm: true,
            //     endpoint: base_url+"/app/common/deleteUpload",
            //     params  : {
            //         _token  : jQuery("input[name='_token']").val(),
            //     }
            // },
            thumbnails: {
                placeholders: {
                    waitingPath: baseurl+'/public/assets/fine-uploader/placeholders/waiting-generic.png',
                    notAvailablePath: baseurl+'/public/assets/fine-uploader/placeholders/not_available-generic.png'
                }
            },
            multiple: true,
            autoUpload: true,
            validation: {
                itemLimit: 5,
                acceptFiles: 'image/*',
                allowedExtensions: ['jpeg', 'jpg', 'gif', 'png']
            },
            callbacks: {
                onError: function(id, name, errorReason, xhrOrXdr) {
                    alert(qq.format("Error on file number {} - {}.  Reason: {}", id, name, errorReason));
                },
                onSubmit: function(event, id, fileName, responseJSON) {
                  
                },
                onComplete: function(id, fileName, response, xhr) {
                    if (response.success) {
                        
                        jsonLocation.push(response.location);
                        jsonLocationPath.push(response.locationName);

                        jQuery('#new_file_mult').val(jsonLocation);                        
                        jQuery('#new_filename_mult').val(jsonLocationPath);
                    }
                },
                // onSubmitDelete: function(id) {
                //     this.setDeleteFileParams({
                //         _token : jQuery("input[name='_token']").val(),
                //         filepath: jQuery("input[name='file']").val(),
                //         filename: jQuery("input[name='filename']").val(),
                //     }, id);
                // },
                // onDeleteComplete: function(id, fileName, response, xhr) {
                //     if (response.success) {
                //         jQuery('#new_file').val('');                        
                //         jQuery('#new_filename').val('');
                //     }
                // },          
            }            
        }); 
    </script>
   
@endsection