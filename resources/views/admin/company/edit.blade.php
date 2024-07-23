@extends('admin.layout.app')
@section('title','Create Client')
@push('headerscript')

<link href="{{ asset('theme/plugins/summernote/summernote.css') }}" rel="stylesheet" />
<style>
  .summernote{
    position: absolute;
    flex: initial;
  }
</style>
@endpush
@section('content')
<!-- ============================================================== -->
<!-- Start right Content here -->
<!-- ============================================================== -->
<div class="content-page">
  <!-- Start content -->
  <div class="content">
    <div class="container">

      <div class="row">
        <div class="col-xs-12">
          <div class="page-title-box">
            <h4 class="page-title">Edit Company</h4>
            <a href="{{ route('company.index') }}" class="btn btn-primary btn-sm pull-right"><i class="fa fa-backward"></i> Back</a>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
      <!-- end row -->
      <div class="row">
        <div class="col-sm-12">
          <div class="card-box">
            {!! Form::model($companyEdit, ['method'=>'patch','files'=>true,'route' => ['company.update', $companyEdit->user_id]]) !!}
              @csrf
              <div class="row form-group">
                <div class="col-sm-6">
                    <label class="">Company Name<i class="text-danger ">*</i></label>
                    <input type="company_name" name="company_name" id="company_name" value="{{ old('company_name',$companyEdit->company_name) }}" class="form-control" required="">
                    <div class="text-danger">{{ $errors->first('company_name') }}</div>
                  </div>  
                <div class="col-sm-6">
                  <label class="">Mobile Number<i class="text-danger ">*</i></label>
                  <input type="text" class="form-control number" name="phone" required="" minlength="10" maxlength="10" value="{{ old('phone',$companyEdit->phone_number) }}">
                  <div class="text-danger">{{ $errors->first('phone') }}</div>
                </div>
              </div>              
              <div class="row form-group">
                <div class="col-sm-6">
                  <label class="">Email<i class="text-danger ">*</i></label>
                  <input type="email" name="email" id="email" value="{{ old('email',$companyEdit->email) }}" class="form-control" required="">
                  <div class="text-danger">{{ $errors->first('email') }}</div>
                </div>     
                
                <div class="col-sm-6">
                  <label class="">Company Zip<i class="text-danger ">*</i></label>
                  <input type="company_zip" name="company_zip" id="company_zip" value="{{ old('company_zip',$companyEdit->company_zip) }}" class="form-control" required="">
                  <div class="text-danger">{{ $errors->first('company_zip') }}</div>
                </div>   
              </div>
              <div class="row form-group">
                 <div class="col-sm-6">
                  <label class="">Company Address<i class="text-danger ">*</i></label>
                  <textarea class="form-control" name="company_address">{{ old('address',$companyEdit->company_address) }}</textarea>
                  <div class="text-danger">{{ $errors->first('company_address') }}</div>
                </div> 
              </div>
              <div class="row form-group">
                <div class="col-sm-12">
                  <div class="col-sm-10">
                    <input type="submit" name="submit" class="btn btn-sm btn-primary" value="Submit">
                    <input type="reset" name="reset" value="Cancel" class="btn btn-sm btn-default">
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div> <!-- container -->
  </div> <!-- content -->
</div>
@endsection
@push('footerscript')
<script>
    function myFunction() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
            $('#eye').hide();
            $('#eye_one').show();
        } else {
            x.type = "password";
            $('#eye_one').hide();
            $('#eye').show();
        }
    }
</script>

  <script>
     $('.number').keyup(function(e)
     {
        if (/\D/g.test(this.value))
        {
          this.value = this.value.replace(/\D/g, '');
      }
  });
</script>

@endpush