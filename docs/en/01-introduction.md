# LangGraph PHP SDK FAQ - Basic Concepts

## What is LangGraph PHP SDK?
LangGraph PHP SDK is a PHP implementation of Python's LangGraph library. It allows you to build stateful, multi-agent applications with graph-based logic. The SDK provides the core components needed to create robust and scalable AI workflows.

## What are the main features of LangGraph PHP SDK?
- **Stateful Graphs**: Create graphs where state is passed between nodes, enabling complex, long-running workflows
- **Node & Edge Control**: Define nodes as units of work and use edges to control the flow, including conditional branching
- **Agent System**: A built-in factory for creating and managing AI agents
- **AI Model Integration**: Easily connect to various large language models (e.g., DeepSeek, Qwen)
- **Persistence**: Save and restore the state of your graphs, allowing workflows to be paused and resumed
- **Streaming**: Support for real-time streaming of responses from nodes

## How does LangGraph PHP SDK differ from other workflow engines?
LangGraph PHP SDK focuses on AI workflow orchestration, especially suitable for building complex multi-agent applications. It provides features like state management, conditional branching, persistence, and streaming responses that traditional workflow engines typically lack.