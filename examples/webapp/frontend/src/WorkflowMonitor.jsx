import React, { useState, useEffect, useRef } from 'react';

const WorkflowMonitor = () => {
  const [executionEvents, setExecutionEvents] = useState([]);
  const [isMonitoring, setIsMonitoring] = useState(false);
  const [workflowStats, setWorkflowStats] = useState({
    totalNodes: 0,
    executedNodes: 0,
    status: 'idle'
  });
  const eventsEndRef = useRef(null);

  // Auto-scroll to bottom when new events are added
  useEffect(() => {
    eventsEndRef.current?.scrollIntoView({ behavior: "smooth" });
  }, [executionEvents]);

  // Simulate workflow execution events
  const startMonitoring = () => {
    if (isMonitoring) return;
    
    setIsMonitoring(true);
    setExecutionEvents([]);
    setWorkflowStats({
      totalNodes: 8,
      executedNodes: 0,
      status: 'running'
    });

    // Simulate workflow execution
    const events = [
      { id: 1, type: 'start', node: 'start-node', message: 'Workflow execution started', timestamp: new Date() },
      { id: 2, type: 'processing', node: 'data-ingestion', message: 'Ingesting data from sources', timestamp: new Date(Date.now() + 1000) },
      { id: 3, type: 'success', node: 'data-ingestion', message: 'Data ingestion completed successfully', timestamp: new Date(Date.now() + 2000) },
      { id: 4, type: 'processing', node: 'data-processing', message: 'Processing ingested data', timestamp: new Date(Date.now() + 3000) },
      { id: 5, type: 'warning', node: 'data-processing', message: 'Detected anomalies in data, applying filters', timestamp: new Date(Date.now() + 4000) },
      { id: 6, type: 'success', node: 'data-processing', message: 'Data processing completed', timestamp: new Date(Date.now() + 5000) },
      { id: 7, type: 'processing', node: 'analysis', message: 'Running analytical models', timestamp: new Date(Date.now() + 6000) },
      { id: 8, type: 'success', node: 'analysis', message: 'Analysis completed successfully', timestamp: new Date(Date.now() + 7000) },
      { id: 9, type: 'processing', node: 'report-generation', message: 'Generating final report', timestamp: new Date(Date.now() + 8000) },
      { id: 10, type: 'success', node: 'report-generation', message: 'Report generation completed', timestamp: new Date(Date.now() + 9000) },
      { id: 11, type: 'end', node: 'end-node', message: 'Workflow execution completed successfully', timestamp: new Date(Date.now() + 10000) }
    ];

    let eventIndex = 0;
    const totalEvents = events.length;
    
    const interval = setInterval(() => {
      if (eventIndex < totalEvents) {
        const event = events[eventIndex];
        setExecutionEvents(prev => [...prev, event]);
        
        // Update stats
        if (event.type === 'success') {
          setWorkflowStats(prev => ({
            ...prev,
            executedNodes: prev.executedNodes + 1
          }));
        }
        
        eventIndex++;
      } else {
        clearInterval(interval);
        setIsMonitoring(false);
        setWorkflowStats(prev => ({
          ...prev,
          status: 'completed'
        }));
      }
    }, 1000);
  };

  const stopMonitoring = () => {
    setIsMonitoring(false);
    setWorkflowStats(prev => ({
      ...prev,
      status: 'stopped'
    }));
  };

  const clearEvents = () => {
    setExecutionEvents([]);
    setWorkflowStats({
      totalNodes: 0,
      executedNodes: 0,
      status: 'idle'
    });
  };

  const getEventIcon = (type) => {
    switch (type) {
      case 'start':
        return (
          <svg className="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        );
      case 'end':
        return (
          <svg className="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        );
      case 'processing':
        return (
          <svg className="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
          </svg>
        );
      case 'success':
        return (
          <svg className="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        );
      case 'warning':
        return (
          <svg className="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
          </svg>
        );
      case 'error':
        return (
          <svg className="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        );
      default:
        return (
          <svg className="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        );
    }
  };

  const getStatusColor = (status) => {
    switch (status) {
      case 'running': return 'bg-green-100 text-green-800';
      case 'completed': return 'bg-blue-100 text-blue-800';
      case 'stopped': return 'bg-yellow-100 text-yellow-800';
      default: return 'bg-gray-100 text-gray-800';
    }
  };

  return (
    <div className="bg-white rounded-lg shadow-lg overflow-hidden">
      <div className="bg-indigo-600 text-white p-4">
        <div className="flex justify-between items-center">
          <h2 className="text-xl font-semibold">Workflow Monitor</h2>
          <div className="flex space-x-2">
            <button
              onClick={isMonitoring ? stopMonitoring : startMonitoring}
              className={`px-4 py-2 rounded-md text-sm font-medium transition duration-200 ${
                isMonitoring
                  ? 'bg-red-500 hover:bg-red-600 text-white'
                  : 'bg-green-500 hover:bg-green-600 text-white'
              }`}
            >
              {isMonitoring ? 'Stop Monitoring' : 'Start Monitoring'}
            </button>
            <button
              onClick={clearEvents}
              className="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md text-sm font-medium transition duration-200"
            >
              Clear
            </button>
          </div>
        </div>
      </div>

      <div className="p-4 border-b border-gray-200">
        <div className="flex items-center justify-between">
          <div className="flex items-center">
            <span className={`inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ${getStatusColor(workflowStats.status)}`}>
              Status: {workflowStats.status.charAt(0).toUpperCase() + workflowStats.status.slice(1)}
            </span>
          </div>
          <div className="flex space-x-6">
            <div className="text-center">
              <p className="text-2xl font-bold text-indigo-600">{workflowStats.executedNodes}</p>
              <p className="text-sm text-gray-500">Nodes Executed</p>
            </div>
            <div className="text-center">
              <p className="text-2xl font-bold text-indigo-600">{workflowStats.totalNodes}</p>
              <p className="text-sm text-gray-500">Total Nodes</p>
            </div>
            <div className="text-center">
              <p className="text-2xl font-bold text-indigo-600">
                {workflowStats.totalNodes > 0 
                  ? Math.round((workflowStats.executedNodes / workflowStats.totalNodes) * 100) 
                  : 0}%
              </p>
              <p className="text-sm text-gray-500">Progress</p>
            </div>
          </div>
        </div>
      </div>

      <div className="p-4 h-96 overflow-y-auto bg-gray-50">
        {executionEvents.length === 0 ? (
          <div className="flex flex-col items-center justify-center h-full text-gray-500">
            <svg className="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <p className="mt-4 text-lg">No execution events yet</p>
            <p className="text-sm mt-2">Start monitoring to see workflow execution events</p>
          </div>
        ) : (
          <div className="space-y-3">
            {executionEvents.map((event) => (
              <div key={event.id} className="bg-white rounded-lg shadow p-4 border border-gray-200">
                <div className="flex items-start">
                  <div className="flex-shrink-0 mt-1">
                    {getEventIcon(event.type)}
                  </div>
                  <div className="ml-3 flex-1">
                    <div className="flex items-center justify-between">
                      <span className="font-medium text-gray-900">{event.node}</span>
                      <span className="text-xs text-gray-500">
                        {event.timestamp.toLocaleTimeString()}
                      </span>
                    </div>
                    <p className="mt-1 text-sm text-gray-600">{event.message}</p>
                    <div className="mt-2">
                      <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {event.type}
                      </span>
                    </div>
                  </div>
                </div>
              </div>
            ))}
            <div ref={eventsEndRef} />
          </div>
        )}
      </div>
    </div>
  );
};

export default WorkflowMonitor;