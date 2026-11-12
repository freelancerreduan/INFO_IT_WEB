<div class="modal fade" id="viewStudentModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title font-display">Student Details</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body" id="viewStudentBody"></div>
      <div class="modal-footer"><button class="btn btn-outline-accent" data-bs-dismiss="modal">Close</button></div>
    </div>
  </div>
</div>

<div class="modal fade" id="viewCourseModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title font-display">Course Details</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body" id="viewCourseBody"></div>
      <div class="modal-footer"><button class="btn btn-outline-accent" data-bs-dismiss="modal">Close</button></div>
    </div>
  </div>
</div>

{{-- Order / Transaction "View" (eye button) --}}
<div class="modal fade" id="viewDetailsModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title font-display" id="viewDetailsTitle">Details</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
      <div class="modal-body" id="viewDetailsBody"></div>
      <div class="modal-footer"><button class="btn btn-outline-accent" data-bs-dismiss="modal">Close</button></div>
    </div>
  </div>
</div>

<div class="modal fade" id="confirmModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center py-4">
        <div class="mx-auto mb-3 d-flex align-items-center justify-content-center" style="width:56px;height:56px;border-radius:50%;background:var(--bad-soft);">
          <i class="bi bi-exclamation-triangle fs-4" style="color:var(--bad)"></i>
        </div>
        <h5 class="font-display" id="confirmTitle">Delete this item?</h5>
        <p class="text-muted mb-4" id="confirmText">This action cannot be undone.</p>
        <div class="d-flex gap-2 justify-content-center">
          <button class="btn btn-outline-accent px-4" data-bs-dismiss="modal">Cancel</button>
          <button class="btn btn-soft-danger px-4" id="confirmActionBtn">Confirm</button>
        </div>
      </div>
    </div>
  </div>
</div>

{{-- Hidden form used by the confirm modal (DELETE / PATCH status) --}}
<form id="confirmForm" method="POST" class="d-none">
  @csrf
  <input type="hidden" name="_method" id="confirmFormMethod" value="DELETE">
  <input type="hidden" name="status" id="confirmFormStatus" value="">
</form>
