<?php

namespace App\Traits;

trait ValidationTrait
{
    protected $storyCreateValidation = [
        'rules' => [
            'story-title' => 'required',
            'poster-portrait-src' => 'required',
        ],
        'messages' => [
            'story-title.required' => 'Story title is required',
            'poster-portrait-src.required' => 'Poster  is required',
        ]
    ];

    protected $storyPageValidation = [
        'rules' => [
            'page-title.title-text' => 'max:26',
            'page-title.title-color' => 'regex:/^#[a-f0-9]{6}$/i',
            'page-description.description-text' => 'max:120',
            'page-description.description-color' => 'regex:/^#[a-f0-9]{6}$/i',
            'button-info.button-text' => 'max:40',
            'button-info.button-color' => 'regex:/^#[a-f0-9]{6}$/i',
            'button-info.button-bg-color' => 'regex:/^#[a-f0-9]{6}$/i',
        ],
        'messages' => [
            'page-title.title-text.max' => 'Page title should not be greater than 26 characters',
            'page-title.title-color.regex' => 'Enter a valid HexaColor for page title',   
            'page-description.description-text.max' => 'Page description should not be greater than 120 characters',
            'page-description.description-color.regex' => 'Enter a valid HexaColor for page description', 
            'button-info.button-text.max' => 'Button text should not be greater than 40 characters',
            'button-info.button-color.regex' => 'Enter a valid HexaColor for button',  
            'button-info.button-bg-color.regex' => 'Enter a valid HexaColor for button background', 
        ],
    ];





    protected $appCreateValidation = [
        'key' => 'required|min:3',
        'name' => 'required|min:1',
        'host' => 'required|min:4|regex:/\..+/i',
        'status' => 'required',
        'order_total' => 'required|numeric',
        'subtotal' => 'required|numeric',
        'discount_total' => 'numeric',
        'discount_tax' => 'numeric',
        'shipping_cost' => 'required|numeric',
        'shipping_tax' => 'required|numeric',
        'cart_tax' => 'required|numeric',
        'total_tax' => 'required|numeric',
        'sales_tax' => 'required|numeric',
        'amount_paid' => 'numeric',
        'amount_due' => 'numeric',
        'order_date' => 'required|date|before_or_equal:now',
        'source_order_id' => 'required|numeric',
        'customer_is_tax_exempt' => 'required|boolean'
    ];

    protected $customerCreateValidation = [
        'name' => 'required|min:1',
        'email' => 'required|email',
        'customer_number' => 'required|numeric',
        'source_customer_id' => 'required|numeric',
    ];

    protected $orderProductCreateValidation = [
        'source_product_id' => 'required|numeric',
        'sku' => 'required|min:1',
        'name' => 'required|min:1',
        'qty' => 'required|numeric',
        'retail_price' => 'required|numeric',
        'unit_price' => 'required|numeric',
        'subtotal' => 'required|numeric',
        'subtotal_tax' => 'required|numeric',
        'total_price' => 'required|numeric',
        'total_price_tax' => 'required|numeric',
        'tax_rate' => 'required|numeric',
        'product_is_tax_exempt' => 'required|boolean',
    ];

    protected $orderShippingCreateValidation = [
        'first_name'    => 'required|min:1',
        'last_name'     => 'required|min:1',
        'address_1'     => 'required|min:2',
        'city'          => 'required',
        'state'         => 'required',
        'zip'           => 'required|min:1',
        'email'         => 'required|email'
    ];

    protected $orderBillingCreateValidation = [
        'first_name'    => 'required|min:1',
        'last_name'     => 'required|min:1',
        'address_1'     => 'required|min:2',
        'city'          => 'required',
        'state'         => 'required',
        'zip'           => 'required|min:1',
        'email'         => 'required|email'
    ];

    protected $orderProdReturnValidation = [
        'qty'                   => 'required|numeric',
        'return_date'           => 'required|date',
        'is_shipping_included'  => 'boolean',
    ];

    protected $orderProdShippedValidation = [
        'qty'           => 'required|numeric',
        'ship_date'     => 'required|date',
    ];

    protected $orderPaymentValidation = [
        'type'              => 'required',
        'transaction_id'    => 'required',
        'payment_date'      => 'required|date',
        'total'             => 'required|numeric',
        'payment_provider'  => 'required',
        'is_refunded'       => 'required|boolean',
        'refunded_total'    => 'required|numeric',
        'pending_refund'    => 'required|numeric',
    ];

    protected $paymentRefundsValidation = [
        'transaction_id'        => 'required',
        'amount'                => 'required|numeric',
        'app'                   => 'required',
        'key'                   => 'required',
        'secret'                => 'required',
    ];
}
