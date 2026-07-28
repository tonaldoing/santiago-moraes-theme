/**
 * Hero YouTube facade player.
 *
 * Shows album art with a play button. On click, replaces the image
 * with a YouTube iframe (lazy-loaded). Supports regular videos,
 * shortened URLs, and playlists.
 */
(function () {
  'use strict';

  var frame = document.querySelector('.hero__album-frame[data-video-url]');
  if (!frame) return;

  var btn = frame.querySelector('.hero__play-btn');
  if (!btn) return;

  /**
   * Convert any YouTube URL to an embeddable src with autoplay.
   */
  function toEmbedUrl(raw) {
    var url;
    try {
      url = new URL(raw);
    } catch (e) {
      return raw;
    }

    // Playlist: youtube.com/playlist?list=...
    var list = url.searchParams.get('list');
    if (url.pathname === '/playlist' && list) {
      return 'https://www.youtube.com/embed/videoseries?list=' + list + '&autoplay=1';
    }

    // Standard watch URL: youtube.com/watch?v=...
    var v = url.searchParams.get('v');
    if (v) {
      var embed = 'https://www.youtube.com/embed/' + v + '?autoplay=1';
      if (list) embed += '&list=' + list;
      return embed;
    }

    // Short URL: youtu.be/VIDEO_ID
    if (url.hostname === 'youtu.be') {
      var videoId = url.pathname.slice(1);
      return 'https://www.youtube.com/embed/' + videoId + '?autoplay=1';
    }

    return raw;
  }

  btn.addEventListener('click', function () {
    var embedSrc = toEmbedUrl(frame.dataset.videoUrl);

    var iframe = document.createElement('iframe');
    iframe.src = embedSrc;
    iframe.allow = 'autoplay; encrypted-media; fullscreen';
    iframe.allowFullscreen = true;
    iframe.setAttribute('frameborder', '0');
    iframe.setAttribute('title', 'YouTube video player');

    frame.innerHTML = '';
    frame.appendChild(iframe);
    frame.classList.add('hero__album-frame--playing');
  });
})();
