@extends('admin.master')

@section('content')

    <div class="row col s12 m12 l12 info-title">
        <div class="row col s12 m12 l6">
            <h5 class="center-align">Info about User</h5>
            <div class="info-title_hr"></div>
            <div class="row col s12 m12 l12 info-title_info">
                <p>Login <span>{{ $user->login }}</span></p>
                <p>Sex
                    <span>
                        @if ( $user->sex == 0 )
                            Man
                        @else
                            Wooman
                        @endif
                    </span>
                </p>
                <p>Last logged <span>{{ $user->created_at }}</span></p>
            </div>
        </div>

        <div class="row col s12 m12 l6">
            <h5 class="center-align">Info about Post</h5>
            <div class="info-title_hr"></div>
            <div class="row col s12 m12 l12 info-title_info">

            </div>
        </div>
    </div>

    <div class="row col s12 m12 l12 complaint-container">
        <div class="title">
            <h5 class="center-align">Complaint №{{ $data->id }}</h5>
        </div>
        <div class="row input-field col s12">
            <div class="col s6 m12 l6">
                <select>
                    <option value="1"
                            {{ $data->stauts == 2 ? 'checked' : '' }}>Not see</option>
                    <option value="2"
                            {{ $data->stauts == 1 ? 'checked' : '' }}>In process</option>
                    <option value="3"
                            {{ $data->stauts == 0 ? 'checked' : '' }}>Solved</option>
                </select>
                <label>Status</label>
            </div>
            <div class="col s6 m12 l6">
                Bundle: {{ $data->bundle }}
            </div>
        </div>
        <div class="row col s12 m12 l12">
            Description: {{ $data->body }}
        </div>
    </div>

@endsection

@section('other_footer_links')

    <script src="{{ asset('js/admin/admin-materializeMain.js') }}"></script>

@endsection