# LangGraph PHP SDK FAQ - Troubleshooting

## What to do if you encounter installation issues?
1. Ensure your PHP version meets the requirements (7.4+)
2. Ensure Composer is installed
3. Check if the network connection is normal
4. Try clearing the Composer cache: `composer clear-cache`

## What to do if model API calls fail?
1. Check if API keys are configured correctly
2. Confirm keys in environment variables or configuration files
3. Check network connectivity
4. Look at specific error messages for more information

## What to do if workflow execution fails?
1. Check if node functions return state correctly
2. Confirm that edge connections are correct
3. Verify that entry and finish points are set correctly
4. Check exception stack traces to locate the issue

## How to debug workflows?
1. Use state tracking features to view state changes
2. Add log output in node functions
3. Use try-catch blocks to capture and log exceptions
4. Check checkpoints to understand workflow execution progress