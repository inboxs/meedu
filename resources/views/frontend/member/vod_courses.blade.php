@extends('frontend.layouts.member')

@section('member')

    <div class="bg-white rounded shadow py-5">
        <div class="grid grid-cols-4 gap-6">
            <a href="?scene="
               class="block text-center {{!$scene ? 'text-blue-600 hover:text-blue-700' : 'text-gray-500 hover:text-gray-600'}}">
                <div class="mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-8 w-8" viewBox="0 0 20 20"
                         fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M2 6a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1H8a3 3 0 00-3 3v1.5a1.5 1.5 0 01-3 0V6z"
                              clip-rule="evenodd"/>
                        <path d="M6 12a2 2 0 012-2h8a2 2 0 012 2v2a2 2 0 01-2 2H2h2a2 2 0 002-2v-2z"/>
                    </svg>
                </div>
                <div class="text-sm">{{__('已购录播课程')}}</div>
            </a>

            <a href="?scene=videos"
               class="block text-center {{$scene === 'videos' ? 'text-blue-600 hover:text-blue-700' : 'text-gray-500 hover:text-gray-600'}}">
                <div class="mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-8 w-8" viewBox="0 0 20 20"
                         fill="currentColor">
                        <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                    </svg>
                </div>
                <div class="text-sm">{{__('已购录播视频')}}</div>
            </a>

            <a href="?scene=history"
               class="block text-center {{$scene === 'history' ? 'text-blue-600 hover:text-blue-700' : 'text-gray-500 hover:text-gray-600'}}">
                <div class="mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-8 w-8" viewBox="0 0 20 20"
                         fill="currentColor">
                        <path fill-rule="evenodd"
                              d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                              clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="text-sm">{{__('录播课程观看')}}</div>
            </a>

            <a href="?scene=like"
               class="block text-center {{$scene === 'like' ? 'text-blue-600 hover:text-blue-700' : 'text-gray-500 hover:text-gray-600'}}">
                <div class="mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-8 w-8" viewBox="0 0 20 20"
                         fill="currentColor">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </div>
                <div class="text-sm">{{__('录播课程收藏')}}</div>
            </a>
        </div>
    </div>

    <div class="pt-5">
        @if($scene === 'videos')
            @if($records->isNotEmpty())
                @foreach($records as $courseId => $videos)
                    @continue(!isset($courses[$courseId]))
                    <div class="mb-5">
                        <div class="flex items-center px-3">
                            <div class="flex-1 truncate text-gray-500 text-sm">
                                {{$courses[$courseId]['title']}}
                            </div>
                            <div class="ml-5">
                                <a class="text-gray-500 hover:text-gray-600 text-sm"
                                   href="{{route('course.show', [$courses[$courseId]['id'], $courses[$courseId]['slug']])}}">更多</a>
                            </div>
                        </div>
                        <div class="mt-2 bg-white px-5 py-3 shadow rounded">
                            @foreach($videos as $videoItem)
                                <a class="block py-2 flex items-center group"
                                   href="{{route('video.show', [$videoItem['course_id'], $videoItem['id'], $videoItem['slug']])}}">
                                    <div class="flex-shrink-0 mr-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="block h-4 w-4 text-gray-400"
                                             viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd"
                                                  d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="flex-1 text-gray-500 group-hover:text-gray-800">
                                        {{$videoItem['title']}}
                                    </div>
                                    <div class="pl-5 text-sm text-gray-500">
                                        {{duration_humans($videoItem['duration'])}}
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            @else
                @include('frontend.components.none')
            @endif
        @else
            @if($records->isNotEmpty())
                <div class="grid gap-6 grid-cols-2 lg:grid-cols-3">
                    @foreach($records as $recordItem)
                        @continue(!isset($courses[$recordItem['course_id']]))
                        @include('frontend.components.course-item', ['course' => $courses[$recordItem['course_id']]])
                    @endforeach
                </div>
            @else
                @include('frontend.components.none')
            @endif
        @endif
    </div>

    <div class="pt-5">
        {{$records->render('frontend.components.common.paginator')}}
    </div>

@endsection