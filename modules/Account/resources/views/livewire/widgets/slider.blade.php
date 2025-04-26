<div class="section full mt-4">
    <div class="section-heading padding">
        <h2 class="title">{{ __('My Accounts') }}</h2>
        <a class="link" href="app-cards.html">{{ __('View All') }}</a>
    </div>

    <!-- carousel single -->
    <div class="section carousel-single splide">
        <div class="splide__track">
            <ul class="splide__list">
                @forelse ($accounts ?? [] as $account)
                    <li class="splide__slide">
                        <div class="card-block bg-dark">
                            <div class="card-main">
                                <div class="card-button dropdown">
                                    <button class="btn btn-link btn-icon" data-bs-toggle="dropdown" disabled
                                        type="button">
                                        <ion-icon name="ellipsis-horizontal"></ion-icon>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <a class="dropdown-item" href="#">
                                            <ion-icon name="pencil-outline"></ion-icon>Edit
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            <ion-icon name="close-outline"></ion-icon>Remove
                                        </a>
                                        <a class="dropdown-item" href="#">
                                            <ion-icon name="arrow-up-circle-outline"></ion-icon>Upgrade
                                        </a>
                                    </div>
                                </div>

                                <div class="balance">
                                    <span class="label">BALANCE</span>
                                    <h1 class="title">{{ $account->currency ?? '' }} {{ $account->balance }}</h1>
                                </div>
                                <div class="in">
                                    <div class="card-number">
                                        <span class="label">Card Number</span>
                                        •••• 9905
                                    </div>
                                    <div class="bottom">
                                        <div class="card-expiry">
                                            <span class="label">Expiry</span>
                                            12 / 25
                                        </div>
                                        <div class="card-ccv">
                                            <span class="label">CCV</span>
                                            553
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @empty
                    <li class="splide__slide">
                        <div class="card-block bg-dark">
                            <div class="card-main">
                                <div class="card-button dropdown">
                                    <button class="btn btn-link btn-icon" data-bs-toggle="dropdown" disabled
                                        type="button">
                                        <ion-icon name="ellipsis-horizontal"></ion-icon>
                                    </button>
                                    <div class="dropdown-menu d-none dropdown-menu-end"></div>
                                </div>
                                <div class="balance">
                                    <span class="label">BALANCE</span>
                                    <h1 class="title">$ 0.00</h1>
                                </div>
                                <div class="in">
                                    <div class="card-number">
                                        <span class="label">Card Number</span>
                                        •••• 1234
                                    </div>
                                    <div class="bottom">
                                        <div class="card-expiry">
                                            <span class="label">Expiry</span>
                                            12 / 25
                                        </div>
                                        <div class="card-ccv">
                                            <span class="label">CCV</span>
                                            123
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
