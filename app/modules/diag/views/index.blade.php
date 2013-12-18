<table>
	@foreach ($settings as $key => $value)
	<tr>
		<td>{{ $key }}:</td>
		<td>{{ $value }}</td>
	</tr>
	@endforeach
</table>