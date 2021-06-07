@extends('layouts.h5-pure')

@section('css')
    <style>
        body {
            background-color: white;
        }

        @if($video['ban_drag'] === 1)
        .dplayer-bar-wrap {
            display: none
        }
        @endif
    </style>
@endsection

@section('content')

    @include('h5.components.topbar', ['title' => '视频', 'back' => route('course.show', [$course['id'], $course['slug']])])

    <div class="video-play-box">
        @if($user)
            @if($canSeeVideo)
                @include('h5.components.player.dplayer', ['videoItem' => $video, 'isTry' => false])
                @if($nextVideo)
                    <div class="text-center watched-over">
                        <p>
                            <a class="next-video-button"
                               href="{{route('video.show', [$nextVideo['course_id'], $nextVideo['id'], $nextVideo['slug']])}}">下一集</a>
                        </p>
                        <p>
                            <a href="javascript:void(0)" onclick="window.location.reload()" class="repeat-view-button">重新观看</a>
                        </p>
                    </div>
                @else
                    <div class="text-center watched-over">
                        <span style="color: white">厉害，当前课程已完成！</span>
                    </div>
                @endif
            @else
                @if($trySee)
                    @include('h5.components.player.dplayer', ['videoItem' => $video, 'isTry' => true])
                    <div style="margin-top: 60px;display: none;" class="text-center watched-over show-buy-course-model">
                        <span style="color: white">您当前观看的是试看内容，如需观看完整内容请订阅课程！</span>
                    </div>
                @else
                    <div style="padding-top: 60px;" class="text-center">
                        <a href="javascript:void(0)" class="btn btn-primary btn-sm show-buy-course-model">请先购买</a>
                    </div>
                @endif

            @endif
        @else
            <div style="margin-top: 60px;" class="text-center">
                <a class="btn btn-primary" href="{{route('login')}}">登录</a>
            </div>
        @endif
    </div>

    <div class="course-info-menu">
        <div class="menu-item active" data-dom="course-chapter">
            目录
            <span class="active-bar"></span>
        </div>
        <div class="menu-item" data-dom="course-comment">
            评论
            <span class="active-bar"></span>
        </div>
    </div>

    <div class="course-chapter course-content-tab-item" style="display: block">
        @if($chapters)
            @foreach($chapters as $chapter)
                @if($videosBox = $videos[$chapter['id']] ?? [])@endif
                @if($videosBoxIds = array_column($videosBox, 'id'))@endif
                <div class="chapter-title show-chapter-videos-box" data-dom="chapter-videos-{{$chapter['id']}}">
                    {{$chapter['title']}}
                    <span class="videos-count">
                        <i class="fa {{in_array($video['id'], $videosBoxIds) ? 'fa-angle-up' : 'fa-angle-down'}} fa-lg"></i>
                    </span>
                </div>
                <div class="chapter-videos {{in_array($video['id'], $videosBoxIds) ? 'active' : ''}} chapter-videos-{{$chapter['id']}}">
                    @foreach($videosBox as $videoItem)
                        <a href="{{route('video.show', [$videoItem['course_id'], $videoItem['id'], $videoItem['slug']])}}"
                           class="chapter-video-item {{$videoItem['id'] === $video['id'] ? 'active' : ''}}">
                            <span class="video-title">{{$videoItem['title']}}</span>
                            @if($videoItem['charge'] === 0)
                                <span class="video-label">免费</span>
                            @else
                                @if($videoItem['free_seconds'] > 0)
                                    <span class="video-label">试看</span>
                                @endif
                            @endif
                        </a>
                    @endforeach
                </div>
            @endforeach
        @else
            <div class="chapter-videos" style="display: block">
                @foreach($videos[0] ?? [] as $videoItem)
                    <a href="{{route('video.show', [$videoItem['course_id'], $videoItem['id'], $videoItem['slug']])}}"
                       class="chapter-video-item {{$videoItem['id'] === $video['id'] ? 'active' : ''}}">
                        <span class="video-title">{{$videoItem['title']}}</span>
                        @if($videoItem['charge'] === 0)
                            <span class="video-label">免费</span>
                        @else
                            @if($videoItem['free_seconds'] > 0)
                                <span class="video-label">试看</span>
                            @endif
                        @endif
                    </a>
                @endforeach
            </div>
        @endif
    </div>

    <div class="course-comment course-content-tab-item">
        <div class="comment-list-box">
            @forelse($comments as $commentItem)
                <div class="comment-list-item">
                    <div class="comment-user-avatar">
                        <img src="{{$commentUsers[$commentItem['user_id']]['avatar']}}" width="32"
                             height="32">
                    </div>
                    <div class="comment-content-box">
                        <div class="comment-user-nickname">{{$commentUsers[$commentItem['user_id']]['nick_name']}}</div>
                        <div class="comment-content">
                            {!! $commentItem['render_content'] !!}
                        </div>
                        <div class="comment-info">
                            <span class="comment-createAt">{{\Carbon\Carbon::parse($commentItem['created_at'])->diffForHumans()}}</span>
                        </div>
                    </div>
                </div>
            @empty
                @include('h5.components.none')
            @endforelse
        </div>
        @if($canComment)
            <div class="video-course-comment-icon show-course-comment-box"
                 data-login="{{$user ? 1 : 0}}"
                 data-login-url="{{route('login')}}">
                <i class="iconfont iconcomment"></i>
            </div>
        @endif
    </div>

    <div class="course-comment-input-box-shadow">
        <div class="course-comment-input-box">
            <div class="title">
                <div class="text">课程评论</div>
            </div>
            <div class="content">
                <textarea name="content" rows="3" placeholder="请输入评论内容"></textarea>
            </div>
            <div class="buttons">
                <a href="javascript:void(0)" class="submit-comment-button comment-button" data-input="content"
                   data-login="{{$user ? 1 : 0}}"
                   data-login-url="{{route('login')}}"
                   data-url="{{route('ajax.video.comment', [$video['id']])}}">发布评论</a>
            </div>
            <div class="options">
                <a href="javascript:void(0)" class="cancel-course-comment close-course-comment-box">取消</a>
            </div>
        </div>
    </div>

    @if(!$canSeeVideo)
        <div class="course-detail-bottom-bar">
            @if($course['charge'] > 0)
                <a href="{{route('member.course.buy', [$course['id']])}}" class="buy-course-button-item button-item">课程
                    ￥{{$course['charge']}}</a>
            @endif
            @if($video['is_ban_sell'] !== 1 && $video['charge'] > 0)
                <a href="{{route('member.video.buy', [$video['id']])}}" class="buy-course-button-item button-item">视频
                    ￥{{$video['charge']}}</a>
            @endif
            <a href="{{route('role.index')}}" class="buy-role-button-item button-item">会员免费看</a>
        </div>
        <div class="course-detail-bottom-bar-block"></div>
    @endif

@endsection

@section('js')
    <script crossorigin="anonymous" integrity="sha384-NowxCVrymxfs88Gx+ygXX3HCvpP7JE1nsDUuIshgWg2gO2eFCaePIdAuOfnG6ZjM"
            src="https://lib.baomitu.com/hls.js/8.0.0-beta.3/hls.min.js"></script>
    <script crossorigin="anonymous"
            integrity="sha512-1t2U1/0xGhBZAriD+/9llOhjPs5nFBDZ7KbnHB4SGwAUPrzyS+02Kus1cz0exk5eMyXxwfHxj/1JLuie/p6xXA=="
            src="https://lib.baomitu.com/dplayer/1.26.0/DPlayer.min.js"></script>
    <script>
        // 微信分享
        window.wechatShareInfo = {
            title: '{{$video['title']}}',
            desc: '{{$video['short_description']}}',
            imgUrl: '{{$course['thumb']}}'
        };
    </script>
@endsection