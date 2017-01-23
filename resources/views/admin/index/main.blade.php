@extends('admin.master')

@section('content')

    @include('admin._patterns._search')

    <form action="{{ action('Admin\ComplaintsController@indexUpdate') }}"
          method="post"
          id="items-list">

        <input type="hidden"
               name="_token"
               value="{{ csrf_token() }}">
        {{ method_field('post') }}

        <div class="row col s12 m12 l12 main-container_content"
             id="mainContent">
            <div v-if="searchStatus">
                @{{ searchData }}
            </div>
            <div class="row main-statistic">
                <div class="title">
                    <h5 class="center-align">Site statistic</h5>
                </div>
                <ul>
                    <li class="row col s12 m12 l12"
                        v-for="item in statistic">
                        <span class="col s11 m10 l11">@{{ item.name }}</span>
                        <span class="col s2 m2 l1">@{{ item.count}}</span>
                    </li>
                </ul>
            </div>
            <div class="col s12 m12 l12 all-complaints">
                <table class="striped">
                    <thead>
                    <tr>
                        <th data-field="id">id</th>
                        <th data-field="multi"></th>
                        <th data-field="author">Author</th>
                        <th data-field="message">Message</th>
                        <th data-field="bundle">Bundle</th>
                        <th data-field="to_post">To post</th>
                        <th data-field="status">Status</th>
                        <th data-field="created">Created</th>
                        <th data-field="edit"></th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse( $complaints as $complaint )
                        <tr>
                            <td>{{ $complaint->id }}</td>
                            <td>
                                <input type="hidden"
                                       name="options[{{ $complaint->id }}]"
                                       value="0">
                                <input type="checkbox"
                                       data-id="{{ $complaint->id }}"
                                       id="options-label-{{ $complaint->id }}"
                                       name="options[{{ $complaint->id }}]"
                                       value="1">
                                <label for="options-label-{{ $complaint->id}}"></label>
                            </td>
                            <td>{{ $complaint->entity_id_user }}</td>
                            <td>{{ $complaint->body }}</td>
                            <td>{{ $complaint->bundle }}</td>
                            <td>{{ $complaint->entity_id_post }}</td>
                            <td>{{ $complaint->status }}</td>
                            <td>{{ $complaint->created_at }}</td>
                            <td><a href="{{ action('Admin\ComplaintsController@edit', ['id' => $complaint->id]) }}"
                                   class="waves-effect waves-light btn edit-button">Edit</a></td>
                        </tr>
                    @empty
                    @endforelse
                    </tbody>
                </table>
                <input type="hidden"
                       name="action"
                       value="notSee">
            </div>
            <div class="row col s12 m12 l12 select-option">
                <div class="input-field col s12">
                    <select class="selected-option"
                            id="items-list-action">
                        <option value="notSee"
                                data-id="notSee">Not see
                        </option>
                        <option value="inProcess"
                                data-id="inProcess">In process
                        </option>
                        <option value="closed"
                                data-id="closed">Closed
                        </option>
                        <option value="delete"
                                data-id="delete">Delete
                        </option>
                    </select>
                </div>
                <div class="row col s12 m12 l12">
                    <a href="#"
                       id="items-list-submit">Продолжить</a>
                </div>
            </div>
        </div>
    </form>

@endsection

@section('other_footer_links')

    <script src="{{ asset('js/admin/admin-mainContent.js') }}"></script>
    <script src="{{ asset('js/admin/admin-materializeMain.js') }}"></script>

@endsection