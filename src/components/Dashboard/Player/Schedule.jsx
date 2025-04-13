export function PlayerSchedule() {
    const schedule = [
      { date: '2025-04-15', type: 'Training', time: '10:00 AM' },
      { date: '2025-04-18', type: 'Match', time: '3:00 PM' },
    ];
    return (
      <section className="bg-white rounded p-4 shadow">
        <h2 className="text-xl font-semibold mb-2">Upcoming Sessions</h2>
        <table className="w-full">
          <thead>
            <tr><th>Date</th><th>Type</th><th>Time</th></tr>
          </thead>
          <tbody>
            {schedule.map((s, i) => (
              <tr key={i}><td>{s.date}</td><td>{s.type}</td><td>{s.time}</td></tr>
            ))}
          </tbody>
        </table>
      </section>
    );
  }
  