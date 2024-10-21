@if ($booking)
    <form id="form-validate" method="POST" action="{{ route('admin.booking.postEditBooking',['id'=>$booking->id]) }}">
        <div class="row ">
            <div class="col-12 order-1">
                <div class="card mb-2 ">
                    <div class="card-header">
                        <h5 class="card-tile mb-0">Thông tin</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="mb-6">
                                    <label class="form-label" for="ecommerce-product-name">Họ tên</label>
                                    <input type="text" class="form-control" placeholder="Họ và tên" name="name"
                                        aria-label="Họ và tên" value="{{ old('name', $booking->name) }}" />
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-6">
                                    <label class="form-label" for="ecommerce-product-name">Số điện thoại</label>
                                    <input type="text" class="form-control" placeholder="Số điện thoại"
                                        name="phone" aria-label="Số điện thoại"
                                        value="{{ old('phone', $booking->phone) }}" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="mb-6">
                                    <label class="form-label" for="ecommerce-product-name">Email</label>
                                    <input type="text" class="form-control" placeholder="Email" name="email"
                                        aria-label="Email" value="{{ old('email', $booking->email) }}" />
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-6 align-items-center pt-1 status">
                                    <span class="mb-0 me-3">Trạng thái</span>
                                    <select name="status" class="select2 form-select">
                                        <option value="0" {{ old('status', $booking->status) == 0 ? 'selected' : '' }}>Đã tạo</option>
                                        <option value="1" {{ old('status', $booking->status) == 1 ? 'selected' : '' }}>Đang xử lý</option>
                                        <option value="2" {{ old('status', $booking->status) == 2 ? 'selected' : '' }}>Hoàn thành</option>
                                        <option value="3" {{ old('status', $booking->status) == 3 ? 'selected' : '' }}>Đã hủy</option>
                                        <option value="4" {{ old('status', $booking->status) == 4 ? 'selected' : '' }}>Tạm hoãn</option>
                                        <option value="5" {{ old('status', $booking->status) == 5 ? 'selected' : '' }}>Đã xác nhận</option>
                                        <option value="6" {{ old('status', $booking->status) == 6 ? 'selected' : '' }}>Không thành công</option>
                                    </select>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="mb-6">
                                    <label class="form-label" for="ecommerce-product-name">Ngày làm</label>
                                    <input type="text" name="date" class="form-control"
                                        placeholder="DD-MM-YYYY HH:MM" id="scheduled_at"
                                        value="{{ old('date', isset($booking->scheduled_at) ? \Carbon\Carbon::parse($booking->scheduled_at)->format('d/m/Y H:i') : '') }}" />
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="mb-6 align-items-center pt-1 service">
                                    <span class="mb-0 me-3">Dịch vụ</span>
                                    <select id="service_id" class="select2 form-select" data-placeholder="Chọn dịch vụ"
                                        name="service_id">
                                        <option value="">Chọn dịch vụ</option>
                                        @forelse($services as $row)
                                            <option value="{{ $row->id }}"
                                                {{ old('service_id', $booking->service_id) == $row->id ? 'selected' : '' }}>
                                                {{ $row->name }}
                                            </option>
                                        @empty
                                            <option value="" disabled>Không có dịch vụ</option>
                                        @endforelse
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="mb-6">
                            <label for="content" class="form-label">Nội dung</label>
                            <textarea class="form-control" rows="3" name="message">{{ old('message', $booking->message) }}</textarea>
                        </div>
                        <div class="mb-6">
                            <label for="content" class="form-label">Ghi chú (nếu có)</label>
                            <textarea class="form-control" rows="3" name="note">{{ old('note', $booking->note) }}</textarea>
                        </div>

                        <div class="d-flex align-content-center flex-wrap gap-4">
                            @csrf
                            @method('PUT')
                            <button type="submit" id="save" class="btn btn-primary">Lưu</button>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>
@else
    <p class="text-center">Đặt lịch không tồn tại</p>
@endif
