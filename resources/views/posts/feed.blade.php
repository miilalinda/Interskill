@extends('layouts.app')

@section('title', 'Feed')

@section('content')

<div class="feed-container">

    <div class="feed-top">
        <h4>Feed</h4>

        <button onclick="location.reload()" class="refresh-btn">
            🔄 Atualizar
        </button>
    </div>

    @forelse ($posts as $post)

        <div class="insta-post">

            <!-- HEADER -->
            <div class="insta-post-header">

                <a href="{{ route('users.show', $post->user->id) }}"
                   class="post-user">

                    <img src="{{ $post->user->foto_perfil
                        ? asset('storage/' . $post->user->foto_perfil)
                        : 'https://ui-avatars.com/api/?name=' . urlencode($post->user->nome) }}"
                        class="post-avatar">

                    <div>
                        <strong>
                            {{ '@' . $post->user->user_nome }}
                        </strong>

                        <span>
                            {{ $post->created_at->diffForHumans() }}
                        </span>
                    </div>

                </a>

                @if ((int) $post->user_id === (int) auth()->id())

                    <div class="post-menu">

                        <button class="menu-btn">
                            ⋯
                        </button>

                        <div class="menu-dropdown">

                            <form method="POST"
                                  action="{{ route('posts.destroy', $post->id) }}">

                                @csrf
                                @method('DELETE')

                                <button>
                                    Excluir
                                </button>

                            </form>

                        </div>

                    </div>

                @endif

            </div>

            <!-- IMAGEM -->
            @if ($post->medias->count())

                <img src="{{ asset('storage/' . $post->medias->first()->caminho) }}"
                     class="insta-post-img"
                     ondblclick="likeAjax({{ $post->id }}, this)">

            @endif

            <!-- BODY -->
            <div class="insta-post-body">

                <!-- ÍCONES -->
                <div class="post-icons">

                    <button onclick="likeAjax({{ $post->id }}, this)"
                            class="icon-btn like-action">

                        <span class="heart-icon">
                            ♡
                        </span>

                    </button>

                    <span class="icon-btn">
                        💬
                    </span>

                </div>

                <!-- CURTIDAS -->
                <p class="likes">
                    {{ $post->likes->count() }} curtidas
                </p>

                <!-- LEGENDA -->
                @if($post->corpo)

                    <p class="caption">

                        <a href="{{ route('users.show', $post->user->id) }}">
                            <strong>
                                {{ '@' . $post->user->user_nome }}
                            </strong>
                        </a>

                        {{ $post->corpo }}

                    </p>

                @endif

                <!-- COMENTÁRIOS -->
                <div class="comments-area">

                    @if($post->comments->count() > 3)

                        <button class="view-comments-btn"
                                onclick="toggleComments(this)">

                            Ver todos os {{ $post->comments->count() }} comentários

                        </button>

                    @endif

                    @foreach ($post->comments->take(3) as $comment)

                        <div class="comment-item">

                            <img src="{{ $comment->user->foto_perfil
                                ? asset('storage/' . $comment->user->foto_perfil)
                                : 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->nome) }}"
                                class="comment-avatar">

                            <div>

                                <strong>
                                    {{ '@' . $comment->user->user_nome }}
                                </strong>

                                <span>
                                    {{ $comment->texto }}
                                </span>

                            </div>

                        </div>

                    @endforeach

                    <!-- ESCONDIDOS -->
                    <div class="hidden-comments d-none">

                        @foreach ($post->comments->skip(3) as $comment)

                            <div class="comment-item">

                                <img src="{{ $comment->user->foto_perfil
                                    ? asset('storage/' . $comment->user->foto_perfil)
                                    : 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->nome) }}"
                                    class="comment-avatar">

                                <div>

                                    <strong>
                                        {{ '@' . $comment->user->user_nome }}
                                    </strong>

                                    <span>
                                        {{ $comment->texto }}
                                    </span>

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

                <!-- FORM -->
                <form action="{{ route('posts.comment', $post->id) }}"
                      method="POST"
                      class="comment-form">

                    @csrf

                    <input type="text"
                           name="texto"
                           placeholder="Adicione um comentário..."
                           required>

                    <button>
                        Publicar
                    </button>

                </form>

            </div>

        </div>

    @empty

        <div class="empty-feed">

            <h4>
                Nenhuma postagem ainda
            </h4>

            <p>
                Siga pessoas ou publique algo no seu perfil.
            </p>

        </div>

    @endforelse

</div>



<script>

function likeAjax(postId, element) {

    fetch(`/posts/${postId}/like`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json"
        }
    })
    .then(res => res.json())
    .then(data => {

        const post = element.closest('.insta-post');

        const likesText = post.querySelector('.likes');

        if (likesText) {
            likesText.innerText = data.likes + ' curtidas';
        }

        const heart = element.querySelector('.heart-icon');

        if (data.liked) {

            heart.innerHTML = '❤️';

            element.classList.add('liked');

        } else {

            heart.innerHTML = '♡';

            element.classList.remove('liked');
        }

    });
}

function toggleComments(button) {

    const area = button.closest('.comments-area');

    const hidden = area.querySelector('.hidden-comments');

    hidden.classList.remove('d-none');

    button.style.display = 'none';
}

</script>



<style>

.feed-container{
    max-width:620px;
    margin:0 auto;
}

.feed-top{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:22px;
}

.feed-top h4{
    color:white;
    font-weight:800;
    margin:0;
}

.refresh-btn{
    background:rgba(255,255,255,0.08);
    color:white;
    border:1px solid rgba(255,255,255,0.14);
    border-radius:10px;
    padding:8px 14px;
    font-size:13px;
    font-weight:700;
}

/* POST */
.insta-post{
    background:rgba(15,23,42,0.86);
    border:1px solid rgba(255,255,255,0.10);
    border-radius:18px;
    overflow:hidden;
    margin-bottom:28px;
    box-shadow:0 18px 45px rgba(0,0,0,0.28);
}

/* HEADER */
.insta-post-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:14px 16px;
}

.post-user{
    display:flex;
    align-items:center;
    gap:12px;
    color:white;
    text-decoration:none;
    transition:0.2s;
}

.post-user:hover{
    opacity:0.85;
    color:white;
}

.post-avatar{
    width:44px;
    height:44px;
    border-radius:50%;
    object-fit:cover;
    padding:2px;
    background:linear-gradient(135deg,#6366f1,#ec4899);
}

.post-user strong{
    display:block;
    font-size:15px;
    color:white;
}

.post-user span{
    display:block;
    font-size:12px;
    color:#94a3b8;
}

/* MENU */
.post-menu{
    position:relative;
}

.menu-btn{
    background:transparent;
    border:none;
    color:white;
    font-size:28px;
    line-height:1;
}

.menu-dropdown{
    display:none;
    position:absolute;
    right:0;
    top:32px;
    background:#1f2937;
    border:1px solid rgba(255,255,255,0.12);
    border-radius:10px;
    padding:8px;
    z-index:10;
}

.post-menu:hover .menu-dropdown{
    display:block;
}

.menu-dropdown button{
    background:transparent;
    color:#ef4444;
    border:none;
    font-weight:700;
    padding:6px 14px;
}

/* IMAGEM */
.insta-post-img{
    width:100%;
    max-height:750px;
    object-fit:cover;
    background:#000;
    transition:0.3s;
}

.insta-post-img:hover{
    transform:scale(1.01);
}

/* BODY */
.insta-post-body{
    padding:13px 16px 16px;
}

/* ICONS */
.post-icons{
    display:flex;
    gap:14px;
    margin-bottom:8px;
    align-items:center;
}

.icon-btn{
    background:transparent;
    border:none;
    color:white;
    font-size:25px;
    padding:0;
    line-height:1;
}

.like-action{
    transition:0.2s;
}

.like-action.liked{
    transform:scale(1.15);
}

.heart-icon{
    font-size:28px;
}

/* CURTIDAS */
.likes{
    color:white;
    font-size:14px;
    font-weight:700;
    margin-bottom:7px;
}

/* LEGENDA */
.caption{
    color:#e5e7eb;
    font-size:14px;
    margin-bottom:12px;
}

.caption a{
    color:white;
    text-decoration:none;
    margin-right:5px;
}

/* COMMENTS */
.comments-area{
    margin-top:10px;
}

.view-comments-btn{
    background:transparent;
    border:none;
    color:#94a3b8;
    padding:0;
    margin-bottom:8px;
    font-size:14px;
}

.comment-item{
    display:flex;
    gap:9px;
    align-items:flex-start;
    margin-bottom:6px;
    padding:3px 0;
}

.comment-avatar{
    width:28px;
    height:28px;
    border-radius:50%;
    object-fit:cover;
}

.comment-item strong{
    color:white;
    font-size:13px;
    margin-right:5px;
}

.comment-item span{
    color:#cbd5e1;
    font-size:13px;
}

/* FORM */
.comment-form{
    border-top:1px solid rgba(255,255,255,0.10);
    margin-top:12px;
    padding-top:12px;
    display:flex;
    gap:10px;
}

.comment-form input{
    flex:1;
    background:transparent;
    border:none;
    color:white;
    outline:none;
    font-size:14px;
}

.comment-form input::placeholder{
    color:#94a3b8;
}

.comment-form button{
    background:transparent;
    border:none;
    color:#818cf8;
    font-weight:700;
}

/* FEED VAZIO */
.empty-feed{
    background:rgba(15,23,42,0.86);
    border:1px solid rgba(255,255,255,0.10);
    border-radius:18px;
    padding:40px 20px;
    text-align:center;
    color:white;
}

.empty-feed h4{
    font-weight:800;
    margin-bottom:8px;
}

.empty-feed p{
    color:#94a3b8;
    margin:0;
}

/* RESPONSIVO */
@media(max-width:700px){

    .feed-container{
        max-width:100%;
    }

    .insta-post{
        border-radius:12px;
    }
}

</style>

@endsection
