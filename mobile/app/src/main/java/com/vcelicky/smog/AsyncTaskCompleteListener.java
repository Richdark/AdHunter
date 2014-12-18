package com.vcelicky.smog;

/**
 * Created by jerry on 24. 11. 2014.
 */

/**
 * This is a useful callback mechanism so we can abstract our AsyncTasks out into separate, re-usable
 * and testable classes yet still retain a hook back into the calling activity. Basically, it'll make classes
 * cleaner and easier to unit test.

 */
public interface AsyncTaskCompleteListener
{
    /**
     * Invoked when the AsyncTask has completed its execution.
     */
    public void onTaskComplete();
}
