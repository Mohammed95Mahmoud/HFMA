<?php


class DataValidation
{
    static function checkSellerData($data)
    {
        if (!empty($data->full_name)
            && !empty($data->address)
            && !empty($data->phone_number)
            && !empty($data->password)
            && !empty($data->email)
            && !empty($data->stor_name)
            && !empty($data->category)
            && !empty($data->logo)
            && !empty($data->type)) {
            return true;
        } else {
            return false;
        }
    }
    static function checkSellerUpdateData($data)
    {
        if (!empty($data->full_name)
            && !empty($data->address)
            && !empty($data->phone_number)
            && !empty($data->password)
            && !empty($data->stor_name)
            && !empty($data->category)
            && !empty($data->type)) {
            return true;
        } else {
            return false;
        }
    }
    static function checkProductData($data)
    {
        if (!empty($data->description)
            && !empty($data->ImageData)
            && !empty($data->name)
            && !empty($data->price)
            && !empty($data->quantity)
            && !empty($data->time)
            && !empty($data->date)
            && !empty($data->saller_id)
            && !empty($data->size)) {
            return true;
        } else {
            return false;
        }
    }

    static function checkProductUData($data)
    {
        if (!empty($data->description)
            && !empty($data->image)
            && !empty($data->name)
            && !empty($data->price)
            && !empty($data->quantity)
            && !empty($data->id)
            && !empty($data->size)) {
            return true;
        } else {
            return false;
        }
    }

    static function checkCustomerData($data)
    {
        if (!empty($data->full_name)
            && !empty($data->address)
            && !empty($data->phone_number)
            && !empty($data->password)
            && !empty($data->email)) {
            return true;
        } else {
            return false;
        }
    }

    static function checkPurchesOrderData($data)
    {
        if (!empty($data->time)
            && !empty($data->date)
            && !empty($data->state)
            && !empty($data->customer_id)) {
            return true;
        } else {
            return false;
        }
    }

    static function checkProblemMessageData($data)
    {
        if (!empty($data->type)
            && !empty($data->content)
            && !empty($data->sender_id)) {
            return true;
        } else {
            return false;
        }
    }

    public static function checkChatData( $data)
    {
        if (!empty($data->time)
            && !empty($data->date)
            && !empty($data->sender_id)
            && !empty($data->message)
            && !empty($data->receiver_id)) {
            return true;
        } else {
            return false;
        }
    }
    public static function checkRateData( $data)
    {
        if (!empty($data->rate)
            && !empty($data->customer_id)
            && !empty($data->saller_id)) {
            return true;
        } else {
            return false;
        }
    }


}