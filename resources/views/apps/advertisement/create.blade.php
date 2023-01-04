@extends('layout.default')

{{-- Styles Section --}}
@section('styles')
    <link href="{{ asset('public/assets/fine-uploader/fine-uploader-gallery.css') }}" rel="stylesheet">

    <style>    
    .imageFile, .videoFile, .previewFile{
        display: none;
    }
    </style>
@endsection

@section('content')
<form action="{{ url('app/advertisement/save') }}" method="post" autocomplete="off" id="form_account" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-lg-12">
            <div class="card card-custom gutter-b example example-compact">
                <div class="card-header bg-pro">
                    <h3 class="card-title color-white">Add Advertisement</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <div class="row">
                                
                                <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                    <label class="col-form-label">{{__('labels.AdvertisementAddName')}} <span class="error">*</span></label>
                                    <input  type="text" name="ad_title" id="ad_title" class="form-control" value="{{old('ad_title')}}" maxlength="50" autocomplete="new-ad_title" required="">
                                </div>
                                
                                <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label">{{__('labels.AdvertisementAddType')}} <span class="error">*</span></label>
                                    <select name="ad_type" id="ad_type" class="form-control" required="">
                                        <option value="">Type</option>
                                        <option value="image">Image</option>
                                        <option value="video">Video</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label">{{__('labels.AdvertisementAddStatu')}} <span class="error">*</span></label>
                                    <select name="ad_status" id="ad_status" class="form-control" required="">
                                        <option value="">Status</option>
                                        <option value="Y">Active</option>
                                        <option value="N">Inactive</option>                                        
                                    </select>
                                </div>

                                <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label">{{__('labels.AdvertisementAddGend')}}</label>
                                    <select name="ad_gender" id="ad_gender" class="form-control">
                                        <option value="">Gender</option>
                                        <option value="F">Female</option>
                                        <option value="M">Male</option>
                                    </select>
                                </div>

                                <div class="form-group col-md-6 col-lg-6 col-sm-12 col-xs-12">
                                    <label class="col-form-label">{{__('labels.AdvertisementAddAge')}}</label>
                                    <select name="ad_age_group" id="ad_age_group" class="form-control">
                                        <option value="">Age</option>
                                        <option value="senior">Senior</option>
                                        <option value="adult">Adult</option>
                                        <option value="young">Young Adult</option>
                                        <option value="child">Youth</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12"> 
                            <div class="row">

                                <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12 text-center previewFile">
                                    <div id="adv_preview" style="display: none;">
                                        <img class="ads_image"  src="" style="border: 1px solid #cdcdcd;">
                                    </div>     
                                    
                                    <video width="400" height="225" controls style="display: none; background-color: black;">
                                        <source src="" type="video/mp4">
                                        <source src="" type="video/webm">
                                        <source src="" type="video/ogg">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                                
                                <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                    <div>
                                        {{-- <label style="font-size: 14px;">
                                            <span style='color:navy;font-weight:bold'>{{__('labels.AdvertisementVideo')}}:</span>
                                        </label> --}}
                                        <ul>
                                            <li>
                                                Please ensure that you upload JPEG, JPG, GIF, PNG file for image.
                                            </li>                                           
                                            <li>
                                                Please ensure that you upload MP4 file for video.
                                            </li>
                                            <li>
                                                The standard specifications are (1920x1080), but can be any 16:9 resolution.
                                            </li>
                                            <li>
                                                You can see the preview of video in the left section. If a video is of 16:9 resolution, it should look perfect in the preview.
                                            </li>
                                        </ul>                                        
                                    </div>
                                    <span id="image-error" class=""></span>
                                </div>

                                <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12 imageFile">
                                    <div id="fine-uploader-gallery"></div>
                                </div>

                                <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12 videoFile">
                                    <div id="fine-uploader-gallery1"></div>
                                </div>
                               
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 text-center">
                        <input type="hidden" name="file" id="new_file" value="" />
                        <input type="hidden" name="filename" id="new_filename" value="" />

                        <button type="submit" class="btn btn-primary">{{__('labels.SAVE')}}</button>
                        <a href="{{ url('app/advertisement') }}"><button type="button" class="btn btn-danger">{{__('labels.CANCEL')}}</button></a>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</form>
@endsection

@section('scripts')
    <script src="{{ asset('public/assets/fine-uploader/fine-uploader.js') }}"></script>
    <script src="{{ asset('js/account/advertisement.js') }}?v={{ config('constants.WEB_VERSION') }}" type="text/javascript"></script>

    <script type="text/template" id="qq-template-gallery">
        <div class="qq-uploader-selector qq-uploader qq-gallery" qq-drop-area-text="Drop files here">
            <div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">
                <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>
            </div>
            <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
                <span class="qq-upload-drop-area-text-selector"></span>
            </div>
            <div class="qq-upload-button-selector qq-upload-button">
                <div>Upload a file</div>
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

    <script>
        function getAspectRatio(width, height) {
                var ratio = width / height;
                return ( Math.abs( ratio - 4 / 3 ) < Math.abs( ratio - 16 / 9 ) ) ? '4:3' : '16:9';
            }
        var ratio = '';
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
                allowedExtensions: ['jpeg', 'jpg', 'gif', 'png'],
                // sizeLimit: 51200 // 50 kB = 50 * 1024 bytes
            },
            callbacks: {
                onError: function(id, name, errorReason, xhrOrXdr) {
                    alert(qq.format("Error on file number {} - {}.  Reason: {}", id, name, errorReason));
                },
                // onValidateBatch: function(fileOrBlobData) {
                //     if(jQuery('#ad_title').val() == ''){
                //         window.alert("Please enter your advertisement name");
                //         return false;
                //     }
                // },
                onSubmitted: function(id,name){
                    var hw = this.getFile(id);
                    var image = new Image(),
                     url = window.URL && window.URL.createObjectURL ? window.URL :
                      window.webkitURL && window.webkitURL.createObjectURL ? window.webkitURL :
                      null;
                    image.onload = function() {
                        console.log(this.width);
                        console.log(this.height);
                        ratio = getAspectRatio(this.width,this.height);
                        console.log(ratio);
                       
                    };
                    image.src = url.createObjectURL(hw);

                    //console.log(hw,"dsds");
                },

                onSubmit: function(event, id, fileName, responseJSON) {
                     //var hw = this.getWidthHeight(id);

                    if(jQuery('#ad_title').val() == ''){
                        window.alert("Please enter your advertisement name");
                        return false;
                    }

                },
                onComplete: function(id, fileName, response, xhr) {
                   
                    if (response.success) {
                      //  console.log(response);
                        jQuery('#new_file').val(response.location);                        
                        jQuery('#new_filename').val(response.locationName);

                        jQuery(".previewFile video").hide();

                        jQuery(".previewFile img").attr('src', response.location);
                        if(ratio == '4:3'){
                            var divW = jQuery("#adv_preview").parents(".col-lg-6").width();
                            jQuery(".previewFile img").css('width',divW-100+"px");
                        }else{
                            jQuery(".previewFile img").css('width',"auto");
                        }
                        jQuery(".previewFile #adv_preview").show();
                        
                        jQuery(".previewFile").show();
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
                    
                    let respon = JSON.parse(fileName.response);                    
                    if (respon.success) {
                        jQuery('#new_file').val('');                        
                        jQuery('#new_filename').val('');

                        jQuery(".previewFile video").hide();

                        jQuery(".previewFile img").attr('src', '');
                        jQuery(".previewFile #adv_preview").hide();
                        jQuery(".previewFile").hide();
                    }
                },          
            }            
        }); 
        
        var galleryUploader = new qq.FineUploader({
            element: document.getElementById("fine-uploader-gallery1"),
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
                acceptFiles: 'video/*',
                allowedExtensions: ['mp4']
            },
            callbacks: {
                onError: function(id, name, errorReason, xhrOrXdr) {
                    alert(qq.format("Error on file number {} - {}.  Reason: {}", id, name, errorReason));
                },
                onSubmit: function(event, id, fileName, responseJSON) {
                    if(jQuery('#ad_title').val() == ''){
                        window.alert("Please enter your advertisement name");
                        return false;
                    }
                },
                onSubmitted: function(id, name){

                },
                onComplete: function(id, fileName, response, xhr) {
                    
                    if (response.success) {

                        jQuery('#new_file').val(response.location);                        
                        jQuery('#new_filename').val(response.locationName);

                        jQuery(".previewFile #adv_preview").hide();

                        jQuery(".previewFile video").attr('src', response.location);
                        jQuery(".previewFile video").show();
                        jQuery(".previewFile").show();
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
                    let respon = JSON.parse(fileName.response);                 
                    if (respon.success) {
                        jQuery('#new_file').val('');                        
                        jQuery('#new_filename').val('');

                        jQuery(".previewFile #adv_preview").hide();

                        jQuery(".previewFile video").attr('src', '');
                        jQuery(".previewFile video").hide();
                        jQuery(".previewFile").hide();
                    }
                },          
            }            
        }); 

         jQuery(document).ready(function(){
            
            function getAspectRatio(width, height) {
                var ratio = width / height;
                return ( Math.abs( ratio - 4 / 3 ) < Math.abs( ratio - 16 / 9 ) ) ? '4:3' : '16:9';
            }
            
            
           
        })
    </script>

@endsection