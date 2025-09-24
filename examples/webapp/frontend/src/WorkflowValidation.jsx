import React, { useState, useRef } from 'react';
import WorkflowVisualization from './WorkflowVisualization';
import WorkflowMonitor from './WorkflowMonitor';

// --- Helper Components ---

const TabButton = ({ label, isActive, onClick }) => (
    <button
        onClick={onClick}
        className={`px-4 py-2.5 text-sm font-medium leading-5 rounded-md transition-colors duration-200 focus:outline-none ${
            isActive
                ? 'bg-blue-600 text-white shadow'
                : 'text-gray-600 hover:bg-gray-200 hover:text-gray-800'
        }`}
    >
        {label}
    </button>
);

const JsonEditor = ({ value, onChange, placeholder, height = 'h-64', disabled = false }) => (
    <textarea
        value={value}
        onChange={onChange}
        placeholder={placeholder}
        className={`w-full ${height} border border-gray-300 rounded-lg p-3 font-mono text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-shadow disabled:bg-gray-100`}
        disabled={disabled}
    />
);

const ActionButton = ({ onClick, disabled, isLoading, children }) => (
    <button
        onClick={onClick}
        disabled={disabled || isLoading}
        className="w-full py-3 px-4 rounded-lg font-medium text-white transition-colors bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 disabled:cursor-not-allowed"
    >
        {isLoading ? (
            <div className="flex items-center justify-center">
                <svg className="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                    <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Validating...</span>
            </div>
        ) : children}
    </button>
);

const ReportCard = ({ report }) => (
    <div className="bg-white shadow-md rounded-lg">
        <div className="p-6 border-b flex items-center gap-3">
            <div className={`w-10 h-10 rounded-full flex items-center justify-center ${report.is_valid ? 'bg-green-100' : 'bg-red-100'}`}>
                <svg className={`w-6 h-6 ${report.is_valid ? 'text-green-600' : 'text-red-600'}`} fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {report.is_valid ? <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /> : <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />} 
                </svg>
            </div>
            <div>
                <h3 className="text-lg font-semibold text-gray-900">Validation {report.is_valid ? 'Successful' : 'Failed'}</h3>
                <p className="text-sm text-gray-500">{report.workflow_name}</p>
            </div>
        </div>
        <div className="grid grid-cols-3 gap-px bg-gray-100 text-center">
            <div className="bg-white p-4"><p className="text-2xl font-bold text-blue-600">{report.summary.total_nodes}</p><p className="text-sm text-gray-500">Nodes</p></div>
            <div className="bg-white p-4"><p className="text-2xl font-bold text-blue-600">{report.summary.total_edges}</p><p className="text-sm text-gray-500">Edges</p></div>
            <div className="bg-white p-4"><p className="text-2xl font-bold text-red-600">{report.summary.error_count}</p><p className="text-sm text-gray-500">Errors</p></div>
        </div>
        {report.errors && report.errors.length > 0 && (
            <div className="p-6">
                <h4 className="font-medium text-gray-800 mb-2">Validation Errors:</h4>
                <ul className="list-disc list-inside space-y-1 bg-red-50 p-4 rounded-md text-red-700 text-sm">
                    {report.errors.map((error, index) => <li key={index}>{error}</li>)}
                </ul>
            </div>
        )}
    </div>
);

// --- Main Component ---

const WorkflowValidation = () => {
  const [workflowDefinition, setWorkflowDefinition] = useState('');
  const [validationRules, setValidationRules] = useState('');
  const [validationEvents, setValidationEvents] = useState([]);
  const [isRunning, setIsRunning] = useState(false);
  const [validationReport, setValidationReport] = useState(null);
  const [activeTab, setActiveTab] = useState('validation');
  const [parsedWorkflow, setParsedWorkflow] = useState(null);

  const handleValidateWorkflow = async () => {
    if (!workflowDefinition.trim() || isRunning) return;

    try {
      const workflow = JSON.parse(workflowDefinition);
      const rules = validationRules.trim() ? JSON.parse(validationRules) : [];
      setParsedWorkflow(workflow);
      setValidationEvents([]);
      setValidationReport(null);
      setIsRunning(true);

      const requestData = { workflow, rules };
      const response = await fetch('http://localhost:8000/api/workflow-validation/validate', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(requestData)
      });
      const data = await response.json();

      if (data.success) {
        setValidationReport(data.data.validation_report);
      } else {
        setValidationReport({ is_valid: false, errors: [data.error || 'Failed to validate workflow'], summary: {}, workflow_name: 'Error' });
      }
    } catch (error) {
        const errorMessage = error instanceof SyntaxError ? 'Invalid JSON format in workflow definition or rules.' : `Network Error: ${error.message}`;
        setValidationReport({ is_valid: false, errors: [errorMessage], summary: {}, workflow_name: 'Error' });
    } finally {
      setIsRunning(false);
    }
  };

  const loadSampleWorkflow = () => {
    const sampleWorkflow = { name: "Sample Workflow", nodes: { start: { type: "start" }, process: { type: "task" }, validate: { type: "decision" }, end: { type: "end" } }, edges: [{ from: "start", to: "process" }, { from: "process", to: "validate" }, { from: "validate", to: "end" }] };
    const sampleRules = [{ type: "required_nodes", nodes: ["start", "end"] }];
    setWorkflowDefinition(JSON.stringify(sampleWorkflow, null, 2));
    setValidationRules(JSON.stringify(sampleRules, null, 2));
    setParsedWorkflow(sampleWorkflow);
  };

  const clearValidation = () => {
    setWorkflowDefinition('');
    setValidationRules('');
    setValidationEvents([]);
    setValidationReport(null);
    setParsedWorkflow(null);
  };

  const renderContent = () => {
    switch (activeTab) {
        case 'visualization': return <div className="bg-white rounded-lg shadow-md h-full"><WorkflowVisualization workflowData={parsedWorkflow} /></div>;
        case 'monitoring': return <div className="bg-white rounded-lg shadow-md h-full"><WorkflowMonitor /></div>;
        case 'validation':
        default:
            return (
                <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 h-full">
                    <div className="bg-white rounded-lg shadow-md flex flex-col p-6 space-y-4">
                        <div className="flex justify-between items-center">
                            <h2 className="text-lg font-semibold text-gray-800">Workflow Input</h2>
                            <div>
                                <button onClick={loadSampleWorkflow} className="text-sm font-medium text-blue-600 hover:text-blue-800 mr-4">Load Sample</button>
                                <button onClick={clearValidation} className="text-sm font-medium text-gray-500 hover:text-gray-700">Clear</button>
                            </div>
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Workflow Definition (JSON)</label>
                            <JsonEditor value={workflowDefinition} onChange={e => setWorkflowDefinition(e.target.value)} placeholder="Enter your workflow definition..." disabled={isRunning} />
                        </div>
                        <div>
                            <label className="block text-sm font-medium text-gray-700 mb-1">Validation Rules (JSON, optional)</label>
                            <JsonEditor value={validationRules} onChange={e => setValidationRules(e.target.value)} placeholder="Enter custom validation rules..." height="h-32" disabled={isRunning} />
                        </div>
                        <div className="pt-2">
                            <ActionButton onClick={handleValidateWorkflow} disabled={!workflowDefinition.trim()} isLoading={isRunning}>Validate Workflow</ActionButton>
                        </div>
                    </div>
                    <div className="bg-white rounded-lg shadow-md flex flex-col p-6">
                        <h2 className="text-lg font-semibold text-gray-800 mb-4">Validation Result</h2>
                        <div className="flex-1">
                            {validationReport ? <ReportCard report={validationReport} /> : (
                                <div className="flex items-center justify-center h-full text-gray-500 text-center">
                                    <p>Define a workflow and click "Validate" to see the results.</p>
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            );
    }
  };

  return (
    <div>
        <h1 className="text-2xl font-semibold text-gray-800 mb-4">Workflow Validation</h1>
        <div className="bg-white rounded-lg shadow-md p-2 flex space-x-2 mb-6">
            <TabButton label="Validation" isActive={activeTab === 'validation'} onClick={() => setActiveTab('validation')} />
            <TabButton label="Visualization" isActive={activeTab === 'visualization'} onClick={() => setActiveTab('visualization')} />
            <TabButton label="Real-time Monitoring" isActive={activeTab === 'monitoring'} onClick={() => setActiveTab('monitoring')} />
        </div>
        <div className="h-[calc(100vh-230px)]">
            {renderContent()}
        </div>
    </div>
  );
};

export default WorkflowValidation;
