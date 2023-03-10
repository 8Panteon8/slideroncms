<?php namespace Winter\Storm\Support\Testing\Fakes;

use Winter\Storm\Mail\Mailable;

class MailFake extends \Illuminate\Support\Testing\Fakes\MailFake
{
    /**
     * Get all of the mailed mailables for a given type.
     *
     * @param  string  $type
     * @return \Illuminate\Support\Collection
     */
    protected function mailablesOf($type)
    {
        return collect($this->mailables)->filter(function ($mailable) use ($type) {
            return $mailable instanceof $type ||
                ($mailable instanceof Mailable && ($mailable->view === $type || $mailable->textView === $type));
        });
    }

    /**
     * Get all of the queued mailables for a given type.
     *
     * @param  string  $type
     * @return \Illuminate\Support\Collection
     */
    protected function queuedMailablesOf($type)
    {
        return collect($this->queuedMailables)->filter(function ($mailable) use ($type) {
            return $mailable instanceof $type ||
                ($mailable instanceof Mailable && ($mailable->view === $type || $mailable->textView === $type));
        });
    }

    /**
     * Send a new message using a view.
     *
     * @param  Mailable|string|array  $view
     * @param  array  $data
     * @param  \Closure|string  $callback
     * @return void
     */
    public function send($view, $data = [], $callback = null)
    {
        if (!$view instanceof Mailable) {
            $view = $this->buildMailable($view, $data, $callback);
        }

        parent::send($view, $data = [], $callback = null);
    }

    /**
     * Queue a new e-mail message for sending.
     *
     * @param  Mailable|string|array  $view
     * @param  array  $data
     * @param  \Closure|string  $callback
     * @param  string|null  $queue
     * @return mixed
     */
    public function queue($view, $data = null, $callback = null, $queue = null)
    {
        if (!$view instanceof Mailable) {
            $view = $this->buildMailable($view, $data, $callback, true);
        }

        return parent::queue($view, $queue = null);
    }

    /**
     * Create a Mailable object from a view file.
     *
     * @param  string|array  $view
     * @param  array  $data
     * @param  \Closure|string  $callback
     * @param  bool  $queued
     * @return \Winter\Storm\Mail\Mailable
     */
    public function buildMailable($view, $data, $callback, $queued = false)
    {
        $html = $text = null;
        $mailable = new Mailable;

        if (is_string($view)) {
            $html = $view;
        } else if (is_array($view)) {
            $html = array_get($view, 0, array_get($view, 'html'));
            $text = array_get($view, 1, array_get($view, 'text'));
        }

        if ($html) {
            if ($queued) {
                $mailable->view($html)->withSerializedData($data);
            } else {
                $mailable->view($html, $data);
            }
        }

        if ($text) {
            if ($queued) {
                $mailable->text($text)->withSerializedData($data);
            } else {
                $mailable->text($text, $data);
            }
        }

        if ($callback !== null) {
            call_user_func($callback, $mailable);
        }
        return $mailable;
    }
}
