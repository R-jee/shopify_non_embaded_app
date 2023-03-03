<form class="row g-4" action="{{ url('/authenticate') }}" method="GET">
    <div class="input-group mb-3">
        <input name="shop" type="text" class="form-control" placeholder="example.myshopify.com" aria-label="Recipient's username" aria-describedby="button-addon2">
        <button class="btn btn-outline-success" type="submit" id="button-addon2">Install</button>
    </div>
</form>
