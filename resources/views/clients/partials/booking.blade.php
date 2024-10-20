<div class="contact-wrap mx-2">
    <div class="section-title">
        <h2>Đặt lịch</h2>
    </div>
    <form id="booking" method="POST" action="{{ route('postService') }}">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-inner">
                    <input type="text" placeholder="Tên của bạn" name="name" value="{{ old('name') }}">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-inner">
                    <input type="text" placeholder="Số điện thoại" name="phone"  value="{{ old('phone') }}">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-inner">
                    <input type="email" placeholder="Địa chỉ email" name="email"  value="{{ old('email') }}">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-inner date">
                    <input autocomplete="off" type="text" id="datepicker3" placeholder="Chọn thời gian"
                        name="date"  value="{{ old('date') }}">
                </div>
            </div>
            <div class="col-lg-12 mb-4">
                <div class="form-inner service">
                    <select id="duration2" name="service" class="w-100">
                        <option value="" selected disabled>Chọn dịch vụ</option>
                        @if (getService() && getService()->isNotEmpty())
                            @foreach (getService() as $service)
                                <option value="{{ $service->slug }}" {{ old('service') == $service->slug ? 'selected' : '' }}>{{ $service->name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-inner">
                    <textarea placeholder="Nội dung" name="content">{{ old('content') }}</textarea>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="form-inner">
                    @csrf
                    <button class="primary-btn3" type="submit">Đặt lịch ngay</button>
                </div>
            </div>
        </div>
    </form>
</div>
