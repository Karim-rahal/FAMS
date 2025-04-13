export function TeamSchedule() {
    const schedule = [
      { date: '2025-04-15', session: 'Tactical Training', time: '10:00 AM' },
      { date: '2025-04-18', session: 'League Match', time: '3:00 PM' },
    ];
    return (
      <section className="bg-white rounded p-4 shadow">
        <h2 className="text-xl font-semibold mb-2">Team Schedule</h2>
        <table className="w-full">
          <thead><tr><th>Date</th><th>Session</th><th>Time</th></tr></thead>
          <tbody>
            {schedule.map((s, i) => (
              <tr key={i}><td>{s.date}</td><td>{s.session}</td><td>{s.time}</td></tr>
            ))}
          </tbody>
        </table>
      </section>
    );
  }