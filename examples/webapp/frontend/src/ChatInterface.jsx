import React, { useState, useRef, useEffect } from 'react';
import Button from './components/ui/Button';

// --- Modern Helper Components ---

const UserAvatar = () => (
  <div className="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white font-semibold text-sm shadow-lg ring-2 ring-blue-100">
    你
  </div>
);

const AssistantAvatar = () => (
  <div className="w-8 h-8 rounded-full bg-gradient-to-br from-gray-700 to-gray-800 flex items-center justify-center shadow-lg ring-2 ring-gray-100">
    <svg className="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707m12.728 0l-.707-.707M12 21v-1m0-16a9 9 0 110 18 9 9 0 010-18z" />
    </svg>
  </div>
);

const ChatMessage = ({ message, isTyping = false }) => {
  const { role, content, timestamp, isError } = message;
  const isUser = role === 'user';

  return (
    <div className={`flex items-start gap-4 animate-in slide-in-from-bottom-2 duration-300 ${isUser ? 'flex-row-reverse' : 'flex-row'}`}>
      {isUser ? <UserAvatar /> : <AssistantAvatar />}
      
      <div className={`group max-w-2xl ${isUser ? 'text-right' : 'text-left'}`}>
        {/* Message bubble */}
        <div className={`inline-block px-4 py-3 rounded-2xl shadow-sm transition-all duration-200 ${
          isUser 
            ? 'bg-gradient-to-br from-blue-500 to-blue-600 text-white' 
            : isError 
              ? 'bg-red-50 text-red-800 border border-red-200' 
              : 'bg-white text-gray-800 border border-gray-200 hover:shadow-md'
        }`}>
          <p className="text-sm leading-relaxed whitespace-pre-wrap">{content}</p>
        </div>
        
        {/* Timestamp */}
        {timestamp && (
          <div className={`text-xs mt-2 px-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 ${
            isUser ? 'text-blue-600' : isError ? 'text-red-500' : 'text-gray-500'
          }`}>
            {timestamp.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}
          </div>
        )}
      </div>
    </div>
  );
};

const TypingIndicator = () => (
  <div className="flex items-start gap-4 animate-in slide-in-from-bottom-2 duration-300">
    <AssistantAvatar />
    <div className="bg-white border border-gray-200 rounded-2xl px-4 py-3 shadow-sm">
      <div className="flex items-center space-x-1">
        <div className="w-2 h-2 bg-blue-400 rounded-full animate-bounce"></div>
        <div className="w-2 h-2 bg-blue-400 rounded-full animate-bounce" style={{ animationDelay: '0.1s' }}></div>
        <div className="w-2 h-2 bg-blue-400 rounded-full animate-bounce" style={{ animationDelay: '0.2s' }}></div>
      </div>
    </div>
  </div>
);

const WelcomeScreen = () => (
  <div className="flex flex-col items-center justify-center h-full text-center py-12 animate-in fade-in duration-500">
    {/* Hero icon */}
    <div className="relative mb-8">
      <div className="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-3xl flex items-center justify-center shadow-2xl">
        <svg className="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
      </div>
      {/* Floating elements */}
      <div className="absolute -top-2 -right-2 w-6 h-6 bg-yellow-400 rounded-full animate-bounce"></div>
      <div className="absolute -bottom-1 -left-2 w-4 h-4 bg-green-400 rounded-full animate-pulse"></div>
    </div>
    
    {/* Welcome text */}
    <h2 className="text-3xl font-bold text-gray-900 mb-4">AI 智能助手</h2>
    <p className="text-lg text-gray-600 mb-8 max-w-md leading-relaxed">
      我是您的智能助手，可以回答问题、协助工作、进行创意讨论。请在下方输入您的问题开始对话。
    </p>
    
    {/* Feature cards */}
    <div className="grid grid-cols-2 gap-4 max-w-lg w-full">
      {[
        { icon: '💡', title: '创意助手', desc: '头脑风暴与创意' },
        { icon: '📚', title: '知识问答', desc: '专业问题解答' },
        { icon: '✍️', title: '文本处理', desc: '写作与编辑' },
        { icon: '🔧', title: '技术支持', desc: '技术问题解决' }
      ].map((item, index) => (
        <div key={index} className="p-4 bg-white rounded-xl border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all duration-200 cursor-pointer group">
          <div className="text-2xl mb-2 group-hover:scale-110 transition-transform duration-200">{item.icon}</div>
          <div className="text-sm font-semibold text-gray-900 mb-1">{item.title}</div>
          <div className="text-xs text-gray-600">{item.desc}</div>
        </div>
      ))}
    </div>
  </div>
);

// --- Main Component ---

const ChatInterface = () => {
  const [messages, setMessages] = useState([]);
  const [inputMessage, setInputMessage] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  const [modelType, setModelType] = useState('qwen');
  const [conversationId, setConversationId] = useState(null);
  const messagesEndRef = useRef(null);
  const textareaRef = useRef(null);

  useEffect(() => {
    messagesEndRef.current?.scrollIntoView({ behavior: "smooth" });
  }, [messages, isLoading]);

  // Auto-resize textarea
  useEffect(() => {
    if (textareaRef.current) {
      textareaRef.current.style.height = 'auto';
      textareaRef.current.style.height = Math.min(textareaRef.current.scrollHeight, 120) + 'px';
    }
  }, [inputMessage]);

  const handleSendMessage = async () => {
    if (!inputMessage.trim() || isLoading) return;

    const userMessage = { 
      id: Date.now(), 
      role: 'user', 
      content: inputMessage, 
      timestamp: new Date() 
    };
    
    setMessages(prev => [...prev, userMessage]);
    setInputMessage('');
    setIsLoading(true);

    try {
      const requestData = {
        message: inputMessage,
        model_type: modelType,
        conversation_id: conversationId || `conv_${Date.now()}`,
        history: messages.map(msg => ({ role: msg.role, content: msg.content }))
      };

      const response = await fetch('http://localhost:8000/api/chat/process', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(requestData)
      });

      const data = await response.json();

      let assistantMessage;
      if (data.success) {
        const finalState = data.data;
        assistantMessage = {
          id: Date.now() + 1,
          role: 'assistant',
          content: finalState.final_response || '抱歉，我无法生成回复。',
          timestamp: new Date()
        };
        setConversationId(finalState.conversation_id);
      } else {
        assistantMessage = { 
          id: Date.now() + 1, 
          role: 'assistant', 
          content: `错误: ${data.error || '获取回复失败'}`, 
          timestamp: new Date(), 
          isError: true 
        };
      }
      setMessages(prev => [...prev, assistantMessage]);

    } catch (error) {
      const errorMessage = { 
        id: Date.now() + 1, 
        role: 'assistant', 
        content: `网络错误: ${error.message}`, 
        timestamp: new Date(), 
        isError: true 
      };
      setMessages(prev => [...prev, errorMessage]);
    } finally {
      setIsLoading(false);
    }
  };

  const handleKeyPress = (e) => {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      handleSendMessage();
    }
  };

  const clearChat = () => {
    setMessages([]);
    setConversationId(null);
  };

  const quickPrompts = [
    "解释一下人工智能的基本概念",
    "帮我写一份项目计划",
    "推荐一些学习编程的资源",
    "分析当前科技发展趋势"
  ];

  return (
    <div className="flex flex-col h-full bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-200/60">
      {/* Modern Header */}
      <div className="bg-gradient-to-r from-blue-50 via-white to-purple-50 border-b border-gray-200/60 p-6">
        <div className="flex justify-between items-center">
          <div className="flex items-center space-x-4">
            <div className="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-600 rounded-2xl flex items-center justify-center shadow-lg">
              <svg className="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
              </svg>
            </div>
            <div>
              <h2 className="text-xl font-bold text-gray-900">AI 智能对话</h2>
              <p className="text-sm text-gray-600">
                {conversationId ? `对话 ID: ${conversationId.slice(-8)}` : '开始新的对话'}
              </p>
            </div>
          </div>
          
          <div className="flex items-center space-x-3">
            {/* Model selector */}
            <div className="relative">
              <select
                value={modelType}
                onChange={(e) => setModelType(e.target.value)}
                className="appearance-none bg-white border border-gray-300 rounded-xl px-4 py-2 pr-8 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 shadow-sm hover:border-gray-400 transition-colors"
              >
                <option value="qwen">🤖 Qwen 模型</option>
                <option value="deepseek">🧠 DeepSeek 模型</option>
              </select>
              <div className="absolute inset-y-0 right-0 flex items-center pr-2 pointer-events-none">
                <svg className="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                </svg>
              </div>
            </div>
            
            {/* Clear button */}
            <Button
              onClick={clearChat}
              variant="ghost"
              size="sm"
              className="text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-xl"
              title="清空对话"
            >
              <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </Button>
          </div>
        </div>
      </div>

      {/* Messages Area */}
      <div className="flex-1 overflow-y-auto">
        <div className="p-6 space-y-6 min-h-full">
          {messages.length === 0 ? (
            <WelcomeScreen />
          ) : (
            <>
              {messages.map((message) => (
                <ChatMessage key={message.id} message={message} />
              ))}
              {isLoading && <TypingIndicator />}
              <div ref={messagesEndRef} />
            </>
          )}
        </div>
      </div>

      {/* Quick Prompts (show when no messages) */}
      {messages.length === 0 && !isLoading && (
        <div className="px-6 pb-4">
          <div className="flex flex-wrap gap-2">
            {quickPrompts.map((prompt, index) => (
              <button
                key={index}
                onClick={() => setInputMessage(prompt)}
                className="px-3 py-2 text-sm bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-lg border border-gray-200 hover:border-gray-300 transition-all duration-200 hover:shadow-sm"
              >
                {prompt}
              </button>
            ))}
          </div>
        </div>
      )}

      {/* Modern Input Area */}
      <div className="border-t border-gray-200/60 bg-gray-50/50 p-6">
        <div className="relative">
          <div className="flex items-end space-x-3">
            {/* Input container */}
            <div className="flex-1 relative">
              <textarea
                ref={textareaRef}
                value={inputMessage}
                onChange={(e) => setInputMessage(e.target.value)}
                onKeyPress={handleKeyPress}
                placeholder="输入您的问题..."
                className="w-full border border-gray-300 rounded-2xl px-4 py-3 pr-12 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-none transition-all shadow-sm hover:border-gray-400 bg-white"
                rows="1"
                disabled={isLoading}
                style={{ minHeight: '48px', maxHeight: '120px' }}
              />
              
              {/* Character count */}
              <div className="absolute bottom-2 right-12 text-xs text-gray-400">
                {inputMessage.length}/2000
              </div>
            </div>
            
            {/* Send button */}
            <Button
              onClick={handleSendMessage}
              disabled={isLoading || !inputMessage.trim()}
              loading={isLoading}
              className="rounded-2xl px-6 py-3 shadow-lg hover:shadow-xl transition-all duration-200"
              size="lg"
            >
              {isLoading ? (
                <div className="w-5 h-5 border-2 border-white border-t-transparent rounded-full animate-spin"></div>
              ) : (
                <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
              )}
            </Button>
          </div>
          
          {/* Input hint */}
          <div className="flex items-center justify-between mt-3 text-xs text-gray-500">
            <span>按 Enter 发送，Shift + Enter 换行</span>
            <div className="flex items-center space-x-4">
              <span className="flex items-center space-x-1">
                <div className="w-2 h-2 bg-green-400 rounded-full"></div>
                <span>AI 助手在线</span>
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default ChatInterface;