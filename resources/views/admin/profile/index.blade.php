{{-- Extends layout --}}
@extends('layout.default')

{{-- Content --}}
@section('content')
    <form class="form" method="post" action="{{url('/update-password')}}" id="form" autocomplete="off">
        @csrf
        <div class="row">
            <div class="col-lg-6">
                <div class="card card-custom gutter-b example example-compact">
                    <div class="card-header">
                        <h3 class="card-title">Change Password</h3>
                    </div>
                    <div class="card-body">
                        <div class="mb-0">
                            <div class="row">
                                <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                    <label class="col-form-label">Old Password</label>
                                    <div class="custom-pwd">
                                        <input type="password" name="old_password" class="form-control" placeholder="" value="" autocomplete="old-password" minlength="8" id="old_password">
                                        <i class="far fa-eye togglePassword"  onclick="showOldPassword()"></i>
                                    </div>
                                </div>
                                <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                    <label class="col-form-label">New Password</label>
                                    <div class="custom-pwd">
                                        <input type="password" name="new_password" class="form-control" placeholder="" value="" autocomplete="new-password" minlength="8" id="new_password">
                                        <i class="far fa-eye togglePassword"  onclick="showPassword()"></i>
                                    </div>
                                </div>
                                <div class="form-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                                    <label class="col-form-label">Confirm Password</label>
                                    <div class="custom-pwd">
                                        <input type="password" name="password_confirm" class="form-control" placeholder="" value="" autocomplete="new-password_confirm" minlength="8" id="password_confirm" >
                                        <i class="far fa-eye togglePassword"  onclick="showConfirmPassword()"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 text-center mb-5">
                        <button type="submit" class="btn btn-primary mr-2">Update</button>
                        <a href="{{url('home')}}"><button type="button" class="btn btn-danger">Cancel</button></a>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

{{-- Scripts Section --}}
@section('scripts')
    <script src="{{ asset('js/pages/widgets.js') }}?v={{ config('constants.WEB_VERSION') }}" type="text/javascript"></script>
    <script>
        $(document).ready(function () {
            jQuery.validator.addMethod("validate_password", function(value, element) {
                if(value!=''){
                    if (/^(?=.*\d)(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,255}$/.test(value)) {
                        return true;
                    } else {
                        return false;
                    }
                }else{
                    return true;
                }
            }, "Please enter valid password");
            $('#form').validate({
                rules: {
                    old_password:{
                        required: true,
                    },
                    new_password:{
                         required: true,
                     },
                    password_confirm : {
                        equalTo : "#new_password"
                    }
                },
                messages: {
                    old_password:{  
                        required: "Please enter password",
                        validate_password: "Password must contain at least one uppercase letter, one number, one special character, and must be at least 8 characters long",
                    },
                    new_password:{  
                        required: "Please enter password",
                        validate_password: "Password must contain at least one uppercase letter, one number, one special character, and must be at least 8 characters long",
                    },
                    password_confirm:{
                        equalTo: "Confirm password does not match",
                    }
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                  error.addClass('invalid-feedback');
                  error.addClass('col-md-12');
                  element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    // $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    // $(element).removeClass('is-invalid');
                    // $(element).addClass('is-valid');
                }
            });
        });
        function showPassword() {
            var x = document.getElementById("new_password");
            if(x.value!=''){
                if (x.type === "password") {
                    x.type = "text";
                } else {
                    x.type = "password";
                }
            }
        }
        function showOldPassword() {
            var x = document.getElementById("old_password");
            if(x.value!=''){
                if (x.type === "password") {
                    x.type = "text";
                } else {
                    x.type = "password";
                }
            }
        }
        function showConfirmPassword() {
            var x = document.getElementById("password_confirm");
            if(x.value!=''){
                if (x.type === "password") {
                    x.type = "text";
                } else {
                    x.type = "password";
                }
            }
        }
    </script>
@endsection
