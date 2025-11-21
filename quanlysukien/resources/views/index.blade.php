@extends('layouts.app')
@section('title', 'Trang chủ')

@section('content')
<div>
  <!-- HERO -->
  <section class="hero">
    <video autoplay muted loop playsinline>
      <source src="{{ asset('video/202511061106.mp4') }}" type="video/mp4">
    </video>

    <div class="hero-content">
      <h2 class="hero-title">Sự kiện mới về</h2>
      <div class="hero-sub">
        <h1>Bạn đã sẵn sàng tham gia chưa</h1>
        <p>Khám phá ngay những sự kiện đang diễn ra & sắp tới, đừng bỏ lỡ cơ hội kết nối và trải nghiệm!</p>
        <button class="btn-hero" onclick="window.location.href='{{ route('events.index') }}'">
          Sự kiện sắp diễn ra
        </button>
      </div>
    </div>
  </section>

  <!-- SỰ KIỆN SẮP DIỄN RA -->
  <section class="event-upcoming" data-aos="fade-up">
    <div class="event-upcoming-box">
      <h2>Sự kiện sắp diễn ra</h2>

      @if($upcoming->isEmpty())
        <div class="alert alert-secondary mt-3">
          Hiện chưa có sự kiện nào sắp diễn ra.
        </div>
      @else
        <div class="event-list">
          @foreach ($upcoming as $index => $e)
            <div class="event-card" 
                 data-aos="zoom-in" 
                 data-aos-delay="{{ $index * 150 }}"
                 onclick="window.location.href='{{ route('events.show', $e->event_id) }}'">
              <img src="{{ $e->image_url ?: 'https://picsum.photos/seed/upcoming' . $e->event_id . '/400/220' }}" 
                   alt="Ảnh sự kiện {{ $e->event_name }}">
              <h3>{{ $e->event_name }}</h3>
              <p class="text-muted small">
                {{ \Illuminate\Support\Carbon::parse($e->start_time)->format('d/m/Y H:i') }} –
                {{ \Illuminate\Support\Carbon::parse($e->end_time)->format('d/m/Y H:i') }}
              </p>
              <p class="text-muted small">
                <i class="bi bi-geo-alt"></i> {{ $e->location ?? '—' }}
              </p>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </section>

  <!-- GIỚI THIỆU -->
  <section class="about" data-aos="fade-up">
    <div class="about-wrapper">
      <img src="{{ asset('img/anh-gioi-thieu-1.jpg') }}" alt="ảnh trái" class="about-img left">
      <div class="about-content">
        <h2>Giới thiệu</h2>
        <p>
          Các sự kiện sinh viên tại Học viện Ngân hàng là cơ hội để bạn trải nghiệm, học hỏi và kết nối.
          Từ những buổi hội thảo chuyên đề, workshop kỹ năng, chương trình thiện nguyện đến các sự kiện sôi động, mỗi hoạt động đều mang đến giá trị riêng giúp bạn phát triển toàn diện.
          Tham gia sự kiện không chỉ giúp bạn nâng cao kỹ năng mềm và tích lũy điểm rèn luyện, mà còn mở ra cơ hội gặp gỡ, giao lưu với bạn bè và các chuyên gia.
          Mỗi sự kiện là một dấu ấn, một hành trình đáng nhớ trong quãng đời sinh viên. <br>
          ✨ Hãy tham gia ngay hôm nay để khám phá, cống hiến và tỏa sáng cùng cộng đồng sinh viên Học viện Ngân hàng!
        </p>
      </div>
      <img src="{{ asset('img/anh-gioi-thieu-2.jpg') }}" alt="ảnh phải" class="about-img right">
    </div>
  </section>

  <!-- DANH SÁCH SỰ KIỆN -->
  <section class="event-list-section" data-aos="fade-up">
    <div class="event-list-box">
      <h1 class="title">Danh sách sự kiện</h1>

      @if($featured->isEmpty())
        <div class="alert alert-secondary mt-3">Chưa có sự kiện nào.</div>
      @else
        <div id="event-list" class="event-list">
          @foreach ($featured as $index => $e)
            <div class="event-card {{ $index >= 6 ? 'hidden-event' : '' }}" 
                 data-aos="fade-up" 
                 data-aos-delay="{{ $index * 100 }}"
                 onclick="window.location.href='{{ route('events.show', $e->event_id) }}'">
              <img src="{{ $e->image_url ?: 'https://picsum.photos/seed/featured' . $e->event_id . '/400/220' }}" 
                   alt="Ảnh sự kiện {{ $e->event_name }}">
              <h3 class="mt-2">{{ $e->event_name }}</h3>
              <p class="text-muted small">
                Bắt đầu: {{ \Illuminate\Support\Carbon::parse($e->start_time)->format('d/m/Y H:i') }}
              </p>
              <p class="text-muted small">
                <i class="bi bi-geo-alt"></i> {{ $e->location ?? '—' }}
              </p>
            </div>
          @endforeach
        </div>

        @if($featured->count() > 6)
          <div class="text-center mt-4">
            <button id="btn-show-more" class="btn-hero">Xem thêm</button>
          </div>
        @endif
      @endif
    </div>
  </section>
</div>

<!-- AOS Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script>
  AOS.init({ duration: 1000, once: true });

  // Nút Xem thêm
  document.addEventListener("DOMContentLoaded", function () {
    const btn = document.getElementById("btn-show-more");
    if (btn) {
      btn.addEventListener("click", function () {
        document.querySelectorAll(".hidden-event").forEach((el, i) => {
          el.style.display = "block";
          el.style.animation = `fadeInUp 0.5s ease ${i * 0.1}s forwards`;
        });
        btn.style.display = "none";
      });
    }
  });
</script>

<style>
:root {
  --main-color: #1D227D;
  --text-dark: #222;
  --text-light: #fff;
  --btn-bg: #143f68;
  --btn-border: #0c2f57;
}

/* RESET */
* { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
body { background-color: var(--main-color); color: var(--text-dark); overflow-x: hidden; }

/* HERO */
.hero {
  position: relative;
  height: 90vh;
  display: flex;
  align-items: center;
  justify-content: center;
  color: var(--text-light);
  text-align: center;
  font-family: 'Adobe Caslon Pro', 'Georgia', serif;
}
.hero video {
  position: absolute; top: 0; left: 0;
  width: 100%; height: 100%;
  object-fit: cover;
}
.hero video::before {
  content: "";
  position: absolute;
  inset: 0;
  background: rgba(0,0,0,0.55);
  z-index: 1;
  backdrop-filter: blur(2px);
}
.hero-content { position: relative; z-index: 2; max-width: 800px; }
.hero-title { font-size: 3rem; font-weight: 800; animation: fadeInOut 10s ease-in-out infinite; }
@keyframes fadeInOut {
  0%,15% { opacity: 1; transform: translateY(0); }
  25%,100% { opacity: 0; transform: translateY(-20px); }
}
.hero-sub {
  opacity: 0;
  animation: fadeSub 10s ease-in-out infinite;
}
@keyframes fadeSub {
  0%,15% { opacity: 0; transform: translateY(20px); }
  20%,70% { opacity: 1; transform: translateY(0); }
  85%,100% { opacity: 0; transform: translateY(-20px); }
}
.hero-sub h1 {
  font-size: 3rem;
  font-weight: 700;
  text-shadow: 0 0 15px rgba(255,255,255,0.7);
  margin-bottom: 0.5em;
}
.hero-sub p { font-size: 1.1rem; margin-bottom: 1.8em; }
.btn-hero {
  background-color: #fff;
  color: #333;
  font-weight: 600;
  padding: 12px 30px;
  border: none;
  border-radius: 12px;
  cursor: pointer;
  font-size: 1rem;
  box-shadow: 0 3px 10px rgba(0,0,0,0.25);
  transition: 0.3s;
}
.btn-hero:hover { transform: translateY(-2px); }

/* EVENT CARD */
.event-card {
  width: 320px;
  background-color: #fff;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 2px 12px rgba(0,0,0,0.12);
  padding-bottom: 20px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  cursor: pointer;
  display: block;
}
.event-card:hover {
  transform: translateY(-5px) scale(1.02);
  box-shadow: 0 8px 18px rgba(0,0,0,0.18);
}
.event-card img { width: 100%; height: 200px; object-fit: cover; }
.event-card h3 { margin: 15px; font-size: 1.4rem; font-weight: 700; color: var(--main-color); }
.event-card p { color: #555; font-size: 1rem; padding: 0 15px; margin-bottom: 10px; line-height: 1.6; }

/* ẨN CÁC SỰ KIỆN SAU THỨ 6 */
.hidden-event { display: none; }

/* ANIMATION */
@keyframes fadeInUp {
  from { opacity: 0; transform: translateY(30px); }
  to { opacity: 1; transform: translateY(0); }
}

/* LAYOUT */
.event-upcoming, .event-list-section {
  background-color: var(--main-color);
  display: flex; justify-content: center;
  padding-bottom: 100px;
}
.event-upcoming-box, .event-list-box {
  background-color: #fff;
  border-radius: 8px;
  box-shadow: 0 6px 16px rgba(0,0,0,0.25);
  margin-top: -80px;
  padding: 60px 70px;
  width: 85%;
  max-width: 1200px;
  text-align: center;
  position: relative;
  z-index: 5;
}
.event-list { display: flex; flex-wrap: wrap; gap: 40px; justify-content: flex-start; text-align: left; }
.event-list-box h1, .event-upcoming-box h2 {
  color: var(--main-color);
  font-size: 3rem;
  font-weight: 800;
  margin-bottom: 40px;
  text-shadow: 2px 2px 4px rgba(0,0,0,0.15);
}

/* ABOUT */
.about {
  background-color: var(--main-color);
  color: #fff;
  padding: 100px 0;
}
.about-wrapper { display: flex; align-items: center; justify-content: center; gap: 30px; flex-wrap: wrap; margin: auto; }
.about-img { height: 250px; object-fit: cover; border-radius: 4px; }
.about-content { flex: 1; text-align: center; padding: 0 20px; }
.about-content h2 { font-size: 2.4rem; font-weight: 800; margin-bottom: 20px; }
.about-content p { font-size: 1.05rem; line-height: 1.8; max-width: 600px; margin: auto; }
</style>
@endsection
