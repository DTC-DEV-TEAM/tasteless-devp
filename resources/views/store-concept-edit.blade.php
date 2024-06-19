<!-- First, extends to the CRUDBooster Layout -->
@extends('crudbooster::admin_template')
@section('content')
  <!-- Your html goes here -->
  <div class='panel panel-default'>
    <div class='panel-heading'>Edit Form</div>
    <div class='panel-body'>
      <form method='post' action='{{CRUDBooster::mainpath('edit-save/'.$row->id)}}'>
        @csrf
        <div class='form-group col-md-6' style="display: flex; flex-direction: column; gap: 10px;">
            <div>
                <label>Store Name<span class="text-danger">*</span></label>
                <input type='text' name='name' required class='form-control' value='{{$row->name}}'/>
            </div>
            <div>
                <label>Beach Name<span class="text-danger">*</span></label>
                <input type='text' name='beach_name' required class='form-control' value='{{$row->beach_name}}'/>
            </div>
            <div>
                <label>Concept<span class="text-danger">*</span></label>
                <select name="concept" class='form-control'>
                    @foreach ($stores as $store)
                        <option value="{{ $store->name }}" {{ $row->concept == $store->name ? 'selected' : '' }}>
                            {{ $store->name }}
                        </option>
                    @endforeach
                </select>
                
            </div>
            <div>
                <label>Company ID<span class="text-danger">*</span></label>
                <input type='text' name='fcompanyid' required class='form-control' value='{{$row->fcompanyid}}'/>
            </div>
            <div>
                <label>Branch ID<span class="text-danger">*</span></label>
                <input type='text' name='branch_id' required class='form-control' value='{{$row->branch_id}}'/>
            </div>
            <div>
                <label>Terminal ID<span class="text-danger">*</span></label>
                <input type='text' name='ftermid' required class='form-control' value='{{$row->ftermid}}'/>
            </div>
            <div>
                <label>Status <span class="text-danger">*</span></label>
             <select name="status" class='form-control'>
                @foreach ($status as $status_name)
                <option value="{{ $status_name }}" {{ $row->status == $status_name ? 'selected' : '' }}>
                    {{ $status_name}}
                </option>
            @endforeach
             </select>
            </div>
            <br>
            <div>
                <button class='btn btn-primary' type="submit">Save Changes</button>
            </div>
        </div>
      </form>
    </div>
 
  </div>
@endsection