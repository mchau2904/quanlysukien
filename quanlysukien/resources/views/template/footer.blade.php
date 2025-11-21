<footer class="footer mt-5">
    <div class="container">
        <div class="row">
            {{-- Column 1 --}}
            <div class="col-md-4 mb-4">
                <h5>PHÒNG CÔNG TÁC SINH VIÊN - HVNH</h5>
                <p>Phụ trách các bạn sinh viên trong Học Viện và làm đầu mối quản lý 3 câu lạc bộ: ACC, CAC, CQP</p>
            </div>

            {{-- Column 2 --}}
            <div class="col-md-4 mb-4">
                <h5>THÔNG TIN LIÊN HỆ</h5>
                <p>Địa chỉ: Phòng 101, 102 Tòa nhà A2</p>
                <p>Trụ sở chính Học viện Ngân hàng</p>
                <p>Phone: 0243 852 1853</p>
                <p>Email: hotrosinhvien@hvnh.edu.vn</p>
            </div>

            {{-- Column 3 --}}
            <div class="col-md-4 mb-4">
                <h5>LINK QUAN TRỌNG</h5>
                <ul class="footer-links">
                    <li><a href="{{ route('events.index') }}">Sự kiện</a></li>
                    @auth
                    @if(auth()->user()->role === 'admin')
                    <li><a href="{{ route('report.index') }}">Báo cáo</a></li>
                    <li><a href="{{ route('evaluation.index') }}">Đánh giá</a></li>
                    <li><a href="{{ route('students.index') }}">Quản lý sinh viên</a></li>
                    @endif
                    @endauth
                    <li><a href="{{ route('registrations.mine') }}">Sự kiện của tôi</a></li>

                </ul>
            </div>
        </div>
    </div>
</footer>

<div class="scroll-to-top" id="scrollToTop" title="Lên đầu trang">
    <i class="fas fa-arrow-up"></i>
</div>
<script>
    // Lấy nút
    const scrollBtn = document.getElementById("scrollToTop");

    // Khi bấm → cuộn mượt lên đầu
    scrollBtn.addEventListener("click", () => {
        window.scrollTo({
            top: 0,
            behavior: "smooth"
        });
    });

</script>