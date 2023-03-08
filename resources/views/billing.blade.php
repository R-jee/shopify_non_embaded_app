<style>
    .plan-heading{
        color: #5c5f62;
    }
    .active-plan span.txt{
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
</style>


<div class="container">
    <h1 class="plan-heading">Choose Your Perfect Plan</h1>

    <div class="active-plan mx-auto rounded-full bg-indigo-darker w-full flex items-center">
        <span class="bg-green-dark text-white tracking-wide text-xs w-auto m-2 inline-block rounded-full py-1 px-2">Free Plan</span>
        <span class="txt w-2/3 sm:w-full text-white text-sm text-indigo-lightest">14 Days Remaining</span>
    </div>

    <div class="block" id="pricing_basic">
        <div class="block_container">
            <div class="top">
                <div class="title">Free Plan</div>
            </div>

            <div class="middle">
                <div class="price_amount"><span class="pound_sign">$</span>0.00</div>
                <div class="price_sub">per month</div>
                <div class="features">
                    <ul>
                        <li class="feature1">Up to 2 FTP Accounts</li>
                        <li class="feature2">Up to 10 Databases</li>
                        <li class="feature3">Unlimited Data Transfer</li>
                        <li class="feature4">2GB Online Storage</li>
                    </ul>
                </div>
            </div>

            <div class="bottom">
                <a href="{{ route('free.plan') }}">Get Access</a>
            </div>
        </div>
    </div>


    <div class="block" id="pricing_advanced">
        <div class="block_container">
            <div class="top">
                <div class="title">{{ $plans[0]['name'] }}</div>
            </div>

            <div class="middle">
                <div class="price_amount"><span class="pound_sign">$</span>{{ $plans[0]['price'] }}</div>
                <div class="price_sub">per month</div>
                <div class="features">
                    <ul>
                        <li class="feature1">Up to 10 FTP Accounts</li>
                        <li class="feature2">Up to 100 Databases</li>
                        <li class="feature3">Unlimited Data Transfer</li>
                        <li class="feature4">10GB Online Storage</li>
                    </ul>
                </div>
            </div>

            <div class="bottom">
                <a href="{{ route('billing', ['plan' => $plans[0]->id, 'shop' => $shop ] ) }}">Buy Now</a>
            </div>
        </div>
    </div>

    <div class="block" id="pricing_professional">
        <div class="block_container">
            <div class="top">
                <div class="title">{{ $plans[1]['name'] }}</div>
            </div>

            <div class="middle">
                <div class="price_amount"><span class="pound_sign">$</span>{{ $plans[1]['price'] }}</div>
                <div class="price_sub">per month</div>
                <div class="features">
                    <ul>
                        <li class="feature1">Up to 200 FTP Accounts</li>
                        <li class="feature2">Unlimited Databases</li>
                        <li class="feature3">Unlimited Data Transfer</li>
                        <li class="feature4">50GB Online Storage</li>
                    </ul>
                </div>
            </div>

            <div class="bottom">
                <a href="{{ route('billing', ['plan' => $plans[1]->id, 'shop' => $shop ]) }}">Buy Now</a>
            </div>
        </div>
    </div>



</div>

<style>
    body {
        font: 16px Verdana, Tahoma, Arial, sans-serif;
        background: url('http://cdn.morguefile.com/imageData/public/files/k/kevinrosseel/preview/fldr_2008_11_28/file0001082180876.jpg');
        background-size: cover;
        color: #fff;
    }

    @media all and (max-width: 800px) {
        body {
            font-size: 15px !important;
        }
    }

    .container {
        width: 85%;
        max-width: 1200px;
        margin: 0 auto;
    }

    .block {
        width: 28%;
        margin: 0 2.5% 0 0;
        float: left;
    }

    .block:last-child {
        margin-right: 0 !important;
    }

    .block_container>div {
        padding: 8% 5% 2%;
    }

    .block#pricing_basic>div>div {
        background-color: #ef8006;
    }

    .block#pricing_advanced>div>div {
        background-color: #77bf22;
    }

    .block#pricing_professional>div>div {
        background-color: #1a8aca;
    }

    .block_container {}

    /* --------------- TOP --------------- */
    .top {}

    .title {
        font-weight: bold;
        font-size: 2vw;
        text-align: center;
        line-height: 4vw;
    }

    /* -------------- MIDDLE ------------- */
    .middle {
        height: 280px;
    }

    @media all and (min-width: 501px) and (max-width: 640px) {
        .middle {
            height: 300px;
        }
    }

    @media all and (max-width: 500px) {
        body {
            font-size: 17.5px !important;
        }

        .block {
            width: 100%;
            margin: 6vh 0;
        }

        .title {
            font-size:6vw;
            line-height: 10vw;
        }

        .price_amount {
            font-size: 15vw !important;
            line-height: 20vw !important;
            height: 18vw !important;
        }

        .pound_sign {
            font-size: 8vw !important;
            line-height: 16vw !important;
        }

        .price_sub {
            font-size: 6vw !important;
        }

        .middle {
            height: auto;
        }

        .bottom {
            margin-top: 12px !important;
        }
    }

    .price_amount {
        font-weight: bold;
        font-size: 6vw;
        line-height: 6vw;
        letter-spacing: -.4vw;
        text-align: center;
        height: 7vw;
    }

    .pound_sign {
        font-size: 3vw;
        line-height: 4vw;
        vertical-align: top;
        margin-right: 1vw;
    }

    .price_sub {
        font-weight: bold;
        font-size: 2vw;
        text-align: center;
    }

    .features {}

    .features ul {
        padding-left: 1.5em;
    }

    .features ul li {}

    .feature1 {}

    .feature2 {}

    .feature3 {}

    .feature4 {}

    /* -------------- BOTTOM ------------- */
    .bottom {
        margin-top: 30px;
        padding: 7% 10% !important;
        text-align: center;
    }

    .bottom a {
        color: #fff;
        text-decoration: none;
    }
</style>
